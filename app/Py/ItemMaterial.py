import requests
import re
import sys
import json
from bs4 import BeautifulSoup

if len(sys.argv) > 1:
    arg = sys.argv[1]
else:
    arg = "30411"

def extract_number_from_text(text):
    pattern = r"\s*x\s*(\d+)"
    match = re.search(pattern, text)
    if match:
        number = match.group(1)
        return number
    else:
        return None
    
def extract_number(text):
    numbers = re.findall(r'\d+', text)
    return numbers[0]    
    
def extract_time(text):
    time_pattern = r'\d{2}:\d{2}:\d{2}'
    time = re.search(time_pattern, text)
    if time:
        return time.group()
    else:
        return None


# 배틀아이템, 특수 먼저 처리
battleItemUrl = 'https://lostark.inven.co.kr/dataninfo/craft/?craftfilter='+arg
response = requests.get(battleItemUrl)

if response.status_code == 200:
    html = response.text
    soup = BeautifulSoup(html, 'html.parser')
    table = soup.find_all(class_="list_table")
    items = ""
    returnItems = []
    for element in table:
        tr_elements = element.find_all('tr')
        items = tr_elements

    # 아이템 정보 세팅
    for item in items:
        appendItem = {
            "materials":[]
        }
        item_name_elements = item.find_all('a',class_='name')

        # 이름과 코드값
        for item_info in item_name_elements:
            appendItem['item_code'] = item_info['data-lostark-item-code']
            appendItem['item_name'] = item_info.text
        
        # 재료
        item_material_list = item.find_all('ul',class_='material_list')
        for material in item_material_list:
            material_element = material.find_all('a')
            produce_type = sum(item.get('item_code') == appendItem['item_code'] for item in returnItems)
            appendItem['produce_type'] = str(produce_type)
            for element in material_element:
                appendItem['materials'].append({
                    "target_item_code": appendItem['item_code'],
                    "item_code": element['data-lostark-item-code'],
                    "cost": extract_number_from_text(element.text),
                    "produce_type": str(produce_type)
                })

        # 제작비용
        price_list = item.find_all('p', class_='price')
        for price in price_list:
            if 'gold' in price['class']:
                appendItem['produce_price_type'] = 1
            else :
                appendItem['produce_price_type'] = 2
            appendItem['produce_price'] = extract_number_from_text(price.text)

        # 소요시간 + 필요 활동력
        detail_info_list_wrap = item.find_all('td',class_='detail_info')
        for detail_info_list in detail_info_list_wrap:
            detail_info = detail_info_list.find_all('li')
            for detail in detail_info:
                if '[소요 시간]' in detail.text:
                    appendItem['produce_cost_time'] = extract_time(detail.text)
                if '[필요 활동력]' in detail.text:
                    appendItem['produce_cost'] = extract_number(detail.text)

        returnItems.append(appendItem)
        

    print(json.dumps(returnItems))

else :
    print(response.status_code)