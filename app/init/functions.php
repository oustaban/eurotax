<?php
function do_dump($value, $level = 0)
{
    if ($level == -1) {
        $trans[' '] = '&there4;';
        $trans["\t"] = '&rArr;';
        $trans["\n"] = '&para;';
        $trans["\r"] = '&lArr;';
        $trans["\0"] = '&oplus;';
        return strtr(htmlspecialchars($value), $trans);
    }

    if ($level == 0)
        echo '<pre>';

    $type = gettype($value);
    echo $type;

    if ($type == 'string') {
        echo '(' . strlen($value) . ')';
        $value = do_dump($value, -1);
    } elseif ($type == 'boolean') {
        $value = ($value ? 'true' : 'false');
    } elseif ($type == 'object') {
        $props = get_class_vars(get_class($value));
        echo '(' . count($props) . ') <u>' . get_class($value) . '</u>';
        foreach ($props as $key => $val) {
            echo "\n" . str_repeat("\t", $level + 1) . $key . ' => ';
            do_dump($value->$key, $level + 1);
        }
        $value = '';
    } elseif ($type == 'array') {
        echo '(' . count($value) . ')';
        foreach ($value as $key => $val) {
            echo "\n" . str_repeat("\t", $level + 1) . do_dump($key, -1) . ' => ';
            do_dump($val, $level + 1);
        }
        $value = '';
    }
    echo " <b>$value</b>";
    if ($level == 0)
        echo '</pre>';
}
