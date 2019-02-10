Feature:
  In order to avoid paying financial penalty
  As a reader
  I need to be able to prolong book borrowing period

  Scenario: Prolong book borrowing period
    Given there is library user "john.doe@email.com"
    And "john.doe@email.com" borrowed book "Professional PHP6" with ISBN number "9781234567897"
    And borrowing period of book with ISBN number "9781234567897" by "john.doe@email.com" ends in "01-02-2020"
    And borrowing period for book with ISBN number "9781234567897" can be prolonged by "7" days
    When "john.doe@email.com" prolong his borrowing of book with ISBN "9781234567897"
    Then borrowing period of book with ISBN number "9781234567897" by "john.doe@email.com" should ends in "08-02-2020"
