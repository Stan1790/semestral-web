<?php
class General {
    public static function sanitize($data) {
        return htmlspecialchars(trim($data), ENT_QUOTES, 'UTF-8');
    }

    public static function generateSelectOptions($result, $valueField, $textField, $selectedValue = null) {
        $options = '';
        while ($row = $result->fetch_assoc()) {
            $selected = ($row[$valueField] == $selectedValue) ? 'selected' : '';
            $options .= "<option value=\"{$row[$valueField]}\" $selected>{$row[$textField]}</option>";
        }
        return $options;
    }

    public static function redirect($url, $message = null) {
        if ($message) {
            $_SESSION['flash_message'] = $message;
        }
        header("Location: $url");
        exit;
    }

    public static function displayFlashMessage() {
        if (isset($_SESSION['flash_message'])) {
            echo "<p class=\"flash-message\">{$_SESSION['flash_message']}</p>";
            unset($_SESSION['flash_message']);
        }
    }
}
?>
