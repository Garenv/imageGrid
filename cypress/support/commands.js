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

Cypress.Commands.add("instantType", (selector, text) => {
    if(text) {
        return cy.get(selector).invoke('val', text)
    }
    return cy.get(selector)
})

Cypress.Commands.add('createUser', (name, email, password, ip = "") => {
    if(email in users) { return }

    const newUser = {
        name: name,
        email: email,
        registerPassword: password,
        registerPassword_confirmation: password,
        email_verified_at: "2023-12-29 21:53:59"
    };

    if(ip !== "") { newUser["ip"] = ip }

    cy.request({ method:'POST', url:'/api/createUser', body:newUser, failOnStatusCode: false }).then((response) => {
        let isSuccessful = response.isOkStatusCode || response.status === 409
        expect(isSuccessful, "User created successfully or already exists").to.be.true
        users[email] = { name:password, email: email, password: password };
    });
})

Cypress.Commands.add('createDefaultUser', () => {
    cy.createUser("FakeUsername", "ValidUsername@gmail.com", "GoodFakePassword@1234", "0.0.0.0")
})

Cypress.Commands.add('login', (email, password) => {
    cy.request( { method: 'POST', url: `/api/login`, body: { email: email, password: password }, failOnStatusCode: false } ).then((response) => {
        let isSuccessful = response.isOkStatusCode || response.status === 405 || response.status === 422
        expect(isSuccessful, "User is logged in. Status Code: " + response.status).true
    })
})

Cypress.Commands.add('logout', () => {
    cy.request( { method: 'GET', url: '/api/logout', failOnStatusCode: false } ).then((response) => {
        let isSuccessful = response.isOkStatusCode || response.status === 405 || response.status === 422
        expect(isSuccessful, "Log out is successful. Status Code: " + response.status).true
    })
})

Cypress.Commands.add('deleteUser', (email) => {
    cy.log("Delete user with email: " + email)
    cy.request({ method:'DELETE', url:`/api/deleteUser/${email}`, failOnStatusCode: false } ).then((response) => {
        let isSuccessful = response.isOkStatusCode || response.status === 405 || response.status === 422
        expect(isSuccessful, "User is deleted. Status Code: " + response.status).true
    });
})

Cypress.Commands.add('deleteUserByName', (name) => {
    cy.log("Delete user with email: " + name)
    cy.request({ method:'DELETE', url:`/api/deleteUserByName/${name}`, failOnStatusCode: false } ).then((response) => {
        let isSuccessful = response.isOkStatusCode || response.status === 405 || response.status === 422
        expect(isSuccessful, "User is deleted. Status Code: " + response.status).true
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
        expect(element)
    });
})

Cypress.Commands.add('isNotInViewport', element => {
    cy.get(element).then($el => {
        const window = Cypress.$(cy.state('window'))
        const bottom = window.height()
        const rect = $el[0].getBoundingClientRect()

        expect((
            rect.top >= -2 &&
            rect.left >= 0 &&
            rect.bottom <= bottom &&
            rect.right <= window.width()
        ), "View is within viewport").true
        expect(rect.top).to.be.greaterThan(bottom)
        expect(rect.bottom).to.be.greaterThan(bottom)
        expect(rect.top).to.be.greaterThan(bottom)
        expect(rect.bottom).to.be.greaterThan(bottom)
    })
})

Cypress.Commands.add('isInViewport', element => {
    cy.get(element).then($el => cy.isElementInViewport($el) )
})

Cypress.Commands.add('isElementInViewport', element => {
    const window = Cypress.$(cy.state('window'))
    const bottom = window.height()
    const rect = element[0].getBoundingClientRect()

    expect((
        rect.top <= bottom &&
        rect.bottom <= bottom &&
        rect.left >= 0 &&
        rect.right <= window.width()
    ), "Content is within viewport").true
})

Cypress.Commands.add('checkDetails', (locator, expected) => {
    let summary = expected.title
    let content = expected.content

    cy.get(locator).children('summary').children('span').first().then((element) => {
        expect(element).to.exist
        expect(element.text()).equals(summary)
    })
    let checkContent = function (isVisible, text) {
        cy.get(locator)
            .children('p')
            .scrollIntoView()
            .then(async ($el) => {
                expect($el.get(0).checkVisibility(), "Is full content visible").equals(isVisible)
                if(text === null) return
                expect($el.text()).equals(content)
                cy.isElementInViewport($el)
            });
    }

    checkContent(false, null)
    cy.get(locator).click()
    checkContent(true, content)
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


