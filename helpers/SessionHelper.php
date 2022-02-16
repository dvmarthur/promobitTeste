<?php

class SessionHelper
{


    public function createSession($name, $value)
    {
        $_SESSION[$name] = $value;
        return $this;
    }
    public function addToSession($name, $value, $conc = null)
    {
        if ($conc == null) {
            $_SESSION[$name] += $value;
        } else {
            $_SESSION[$name] .= "." . $value;
        }
        return $this;
    }
    public function updateSession($name, $newValue)
    {
        unset($_SESSION[$name]);
        $_SESSION[$name] = $newValue;
        return $this;
    }
    public function selectSession($name)
    {
        return $_SESSION[$name];
    }
    public function deleteSession($name)
    {
        unset($_SESSION[$name]);
        return $this;
    }
    public function checkSession($name)
    {
        return isset($_SESSION[$name]);
    }
}
