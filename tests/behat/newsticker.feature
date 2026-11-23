@local @local_newsticker @javascript
Feature: Configure the site announcement
  As an administrator
  I can configure an announcement for site visitors

  Scenario: An administrator enables a message and a user sees it
    Given I log in as "admin"
    And I navigate to "Plugins > Local plugins > News Ticker" in site administration
    And I set the following fields to these values:
      | Enable news ticker | 1 |
      | Ticker message | Synthetic maintenance notice |
    And I press "Save changes"
    When I log out
    Then I should see "Synthetic maintenance notice"
