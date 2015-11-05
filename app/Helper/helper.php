<?php

if ( !function_exists('show_selected_option')) {
    function show_selected_option($categories, $selected_id = 0, $class = '', $dataAtrribute = null) {
        $html = '';

        if (count($categories)) return $html;
        $html = $class != '' ? '<select class="'.$class.'">' : '<select>';
        foreach ($categories as $category) {
            $selected = $category->id == $selected_id ? 'selected' : '';
            $html .= '<option value="'.$category->id.'" '.$selected.'>'.$category->name.'</option>';
        }

        $html .= '</select>';

        return $html;
    }
}

if (!function_exists('replace_url_img')) {
    /**
     * Replace url img for render PDF
     * @param  string $string content html
     * @return string         
     */
    function replace_url_img($string) {
        preg_match_all('@src="([^"]+)"+@', $string, $match );
        $srcs = array_pop($match);
        
        foreach ($srcs as $src) {
            $tmp = explode('uploads', $src);
            $replace = 'uploads'.array_pop($tmp);
            $string = str_replace($src, $replace, $string);
        }

        return $string;
    }
}