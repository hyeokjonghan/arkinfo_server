<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\HtmlString;

class XssSanitizer {
    public function handle(Request $request, Closure $next) {
        $input = $request->all();
        array_walk_recursive($input, function(&$input) {
            $input = new HtmlString($input);
            $input = strip_tags($input->toHtml());
        });
        
        $request->merge($input);

        return $next($request);
    }
}