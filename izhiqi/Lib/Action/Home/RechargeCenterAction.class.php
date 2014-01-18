<?php

class RechargeCenterAction extends Action {

    public function _initialize() {
        if (!Loginhelp::isUserLogin()) {
            $this->redirect('/');
        }
        $this->uid = Loginhelp::getUserUid();
    }

    public function index() {

        $this->display();
    }

}

?>