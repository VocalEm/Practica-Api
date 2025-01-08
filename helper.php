<?php
function convertToUtf8($data)
{
    if (is_array($data)) {
        foreach ($data as $key => $value) {
            $data[$key] = convertToUtf8($value);
        }
    } elseif (is_string($data)) {
        return mb_convert_encoding($data, "UTF-8", "auto");
    }
    return $data;
}
