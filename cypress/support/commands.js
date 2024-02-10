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

let users = {}

Cypress.Commands.add("clearType", (selector, text) => {
    if(text) {
        return cy.get(selector).clear().invoke('val', text)
    }
    return cy.get(selector).clear()
})

Cypress.Commands.add('createUser', (name, email, password, failOnStatusCode = true) => {
    if(email in users) { return }

    const newUser = {
        name: name,
        email: email,
        registerPassword: password,
        registerPassword_confirmation: password,
        email_verified_at: "2023-12-29 21:53:59"
    };

    cy.request({ method:'POST', url:'/api/createUser', body:newUser, failOnStatusCode: false }).then((response) => {
        let isSuccessful = response.isOkStatusCode || response.status === 409
        expect(isSuccessful, "User created successfully or already exists").to.be.true
        users[email] = { name:password, email: email, password: password };
    });
})

Cypress.Commands.add('deleteUser', (email) => {
    cy.log("Delete user with email: " + email)
    cy.request({ method:'DELETE', url:`/api/deleteUser/${email}`, failOnStatusCode: false } ).then((response) => {
        let isSuccessful = response.isOkStatusCode || response.status === 405 || response.status === 422
        expect(isSuccessful, "User deleted successfully or does not exist")
        if(email in users) { delete users[email] }
    });
})

Cypress.Commands.add('deleteAllUsers', () => {
    cy.request({ method:'DELETE', url:`/api/deleteAllUsers`, failOnStatusCode: false } ).then((response) => {
        let isSuccessful = response.isOkStatusCode || response.status === 405 || response.status === 422
        expect(isSuccessful, "Users deleted successfully or is empty")
        users = {}
    });
})

Cypress.Commands.add("containsText", (locator, text) => {
    cy.get(locator).then((element) => {
        expect(element.text()).contains(text);
    });
})

Cypress.Commands.add("isText", (locator, text) => {
    cy.get(locator).then((element) => {
        expect(element.text()).equals(text);
    });
})

Cypress.Commands.add("isVisibleWithText", (locator, text) => {
    cy.get(locator).should('be.visible').then((element) => {
        expect(element.text()).equals(text);
    });
})

Cypress.Commands.overwriteQuery('get', function (originalFn, ...args) {
    let selector = args[0]
    let code = selector.charCodeAt(0)
    if(code > 64 && code < 91 || code > 96 && code < 123) {
        args[0] = `[data-cy="${selector}"]`
        return originalFn.apply(this, args)
    }
    return originalFn.apply(this, args)
});


