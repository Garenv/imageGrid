/// <reference types="cypress" />

declare namespace Cypress {
    interface Chainable<Subject> {
        /** Clears text of an element and types the text if is non-empty.
         *
         * @param selector
         * @param text
         */
        clearType(selector: string, text: string): Chainable<any>;

        /**
         * Creates a user.
         * @param name
         * @param email
         * @param password
         * @param failOnStatusCode
         */
        createUser(name: string, email: string, password: string, failOnStatusCode?: boolean): void;
        deleteUser(email: string): void;
        deleteAllUsers(email: string): void;

        containsText(locator: string, text: string): void;
        isText(locator: string, text: string): void;
        isVisibleWithText(locator: string, text: string): void;
        isNotInViewport(locator: string): void;
        isInViewport(locator: string): void;
        isElementInViewport(element: JQuery<HTMLElement>): void;
        checkDetails(locator: string, expected: { title: string, content: string }): void;
        detailsSummaryIsVisible(detailsLocator: string): void;
    }
}
