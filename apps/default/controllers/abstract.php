<?php

class AbstractController extends Controller {
    public function init() {
        $regex  = Settings::getValue("assets", "regex", false);
        $append = Settings::getValue("assets", "append", "");

        if ($regex != false && $append !== "") {
            $append = preg_replace("#".$regex."#", $append, PROJECT_ROOT);
        }

        $this->assign('assetPath', $append);

        $section = null;
        $segments = explode("/", $this->request->getUrl());
        if (count($segments) > 1) {
            $section = $segments[1];
        }
        $this->assign('section', $section);
    }
}