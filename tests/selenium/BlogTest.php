<?php
/**
 * @group selenium
 */

class BlogTest extends SeleniumTestController {
    protected static $fixture_file = "pd_clean";

    public static $browsers = array(
        array(
            "name" => "Firefox",
            "browser" => "*firefox",
            "host" => "ff.vm.linux",
            "port" => 4444,
            "timeout" => 30000,
        ),
    );

    public function setUp() {
        parent::setUp();
        $this->setBrowserUrl(Settings::getValue("site.base_href"));
    }

    public function testHomepageShowsRecentPostsInCorrectOrder() {
        $this->open("/");
        // not massively keen on such tight DOM bounding, but let's see how it goes...
        $this->assertElementPresent("//body/div[@class='container']/div[@class='post'][1]/h2/a[text()='Another Test Post']");
        $this->assertElementPresent("//body/div[@class='container']/div[@class='post'][2]/h2/a[text()='This Is A Test Post']");
    }

    public function testPostLinksFromHomepageAreCorrect() {
        $this->open("/");
        $this->assertElementPresent("//body/div[@class='container']/div[@class='post'][1]/h2/a[@href='/2011/09/another-test-post']");
    }

    public function testStandalonePostPageTitle() {
        $this->open("/2011/09/another-test-post");
        $this->assertTitle("Payne Digital Ltd - Another Test Post");
    }
}
