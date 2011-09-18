<?php
PathManager::loadPaths(
    array("/(?P<month>\d{4}/\d{2})", "view_month"),
    array("/(?P<month>\d{4}/\d{2})/(?P<url>[A-z0-9-]+)", "view_post"),
    array("/(?P<month>\d{4}/\d{2})/(?P<url>[A-z0-9-]+)/comment", "add_comment"),
    array("/(?P<month>\d{4}/\d{2})/(?P<url>[A-z0-9-]+)/comment/thanks", "comment_thanks"),
    array("/tag/(?P<tag>[a-z\s]+)", "search_tags")
);
