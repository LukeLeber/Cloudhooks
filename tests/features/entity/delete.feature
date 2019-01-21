Feature: Delete cloudhook entities
  In order to delete a cloudhook I need to have the "administer cloudhooks" permission.

  Background:
    Given cloudhooks:
      | id     | label  | plugin_id        | event            | weight |
      | test_1 | Test 1 | test_cloudhook_1 | post-code-deploy | 0      |
      | test_2 | Test 2 | test_cloudhook_2 | post-code-update | 1      |
      | test_3 | Test 3 | test_cloudhook_3 | post-db-copy     | 2      |
      | test_4 | Test 4 | test_cloudhook_4 | post-files-copy  | 3      |

  @api
  Scenario: An anonymous user tries to delete an existing cloudhook
    Given I am an anonymous user
    And I go to "/admin/config/cloudhooks/test_1/delete"
    Then I should get a "403" HTTP response
    And I should see "You are not authorized to access this page."

  @api
  Scenario: An authenticated user tries to delete an existing cloudhook without the "administer cloudhooks" permission
    Given I am logged in as a user with the "administer site configuration" permission
    And I go to "/admin/config/cloudhooks/test_1/delete"
    Then I should get a "403" HTTP response
    And I should see "You are not authorized to access this page."

  @api
  Scenario: An authenticated user tries to delete an existing cloudhook with the "administer cloudhooks" permission
    Given I am logged in as a user with the "administer cloudhooks" permission
    And I go to "/admin/config/cloudhooks"
    And I click "Delete" in the "Test 1" row
    Then I should get a "200" HTTP response
    And I should see "Are you sure you want to delete Test 1?"
    Then I press "Delete"
    And I should get a "200" HTTP response
    And I should see "Deleted the Test 1 cloudhook configuration."
