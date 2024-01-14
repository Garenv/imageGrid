/// <reference types="cypress" />

declare namespace Cypress {
    interface Chainable<Subject> {
        /**
         *
         * @param selector
         * @param text
         */
        clearType(selector: string, text: string): Chainable<any>;
    }
}
