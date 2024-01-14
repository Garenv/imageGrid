// ***********************************************
// This example commands.js shows you how to
// create various custom commands and overwrite
// existing commands.
//
// For more comprehensive examples of custom
// commands please read more here:
// https://on.cypress.io/custom-commands
// ***********************************************
//
//
// -- This is a parent command --
// Cypress.Commands.add('login', (email, password) => { ... })
//
//
// -- This is a child command --
// Cypress.Commands.add('drag', { prevSubject: 'element'}, (subject, options) => { ... })
//
//
// -- This is a dual command --
// Cypress.Commands.add('dismiss', { prevSubject: 'optional'}, (subject, options) => { ... })
//
//
// -- This will overwrite an existing command --
// Cypress.Commands.overwrite('visit', (originalFn, url, options) => { ... })

Cypress.Commands.add("clearType", (selector, text) => {
    if(text) {
        return cy.get(selector).clear().type(text)
    }
    return cy.get(selector).clear()
})

Cypress.Commands.add('createUser', (name, email, password, skipFailure = false) => {
    const newUser = {
        name: name,
        email: email,
        registerPassword: password,
        registerPassword_confirmation: password,
        skipMultipleAccounts: true,
        email_verified_at: "2023-12-29 21:53:59"
    };
    return cy.request({method:'POST', url:'/api/createUser', body:newUser, failOnStatusCode: skipFailure});
})
