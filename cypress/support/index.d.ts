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
         * @param skipFailure
         */
        createUser(name: string, email: string, password: string, skipFailure?: boolean): Chainable<any>;
    }
}
