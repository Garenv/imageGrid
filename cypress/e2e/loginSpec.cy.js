describe('user visits the login page', () => {
    let emailSelector = '#email'
    let passwordSelector = '#password'

    before(() => {
        Cypress.on('uncaught:exception', (err, runnable) => {
            return false
        })

    })
    beforeEach(() =>  {
        cy.visit('/login')
    })
    let attemptLogin = function (email, password) {
        if(email) { cy.get(emailSelector).type(email) }
        if(password) { cy.get(passwordSelector).type(password) }
        cy.get('.card-front > .center-wrap > .section > .btn').click()
    };

    describe('user attempts to login with invalid inputs', () => {
        let pleaseFillOut = 'Please fill out this field.';

        [
            ["ValidUsername.com", "NotARealPassword1234", "Please include an '@' in the email address. 'ValidUsername.com' is missing an '@'.", emailSelector],
            ["ValidUsername@", "NotARealPassword1234", "Please enter a part following '@'. 'ValidUsername@' is incomplete.", emailSelector],
            ["", "", pleaseFillOut, emailSelector],
            ["", "NotARealPassword1234", pleaseFillOut, emailSelector],
            ["ValidUsername@gmail.com", "", pleaseFillOut, passwordSelector],
        ].forEach((value, index, array) => {
            let email = value[0]
            let password = value[1]
            let message = value[2]
            let selector = value[3]

            it.only(`should fail with ${message}`, () => {
                attemptLogin(email, password)
                cy.get(selector).then(($t) => {
                    const text = $t[0].validationMessage
                    expect(text).to.eq(message)
                })
            })
        })
    })

    describe('user attempts to login with invalid credentials', () => {
        [
            ["NotARealEmail@gmail.com", "NotARealPassword1234", 'These credentials do not match our records.✖'],
            ["ValidUsername@gmail.com", "NotARealPassword1234", 'These credentials do not match our records.✖'],
            ["ValidUsername@gmail.com", "NotARealPassword1234", 'These credentials do not match our records.✖'],
            ["ValidUsername@gmail.com", "NotARealPassword1234", 'These credentials do not match our records.✖'],
            ["ValidUsername@gmail.com", "NotARealPassword1234", 'Your account has been locked due to too many attempts.✖']
        ].forEach((value, index, array) => {
            let username = value[0]
            let password = value[1]
            let message = value[2]

            it(`should fail with ${message}`, () => {
                attemptLogin(username, password)
                cy.get('.toastify').then(($t) => {
                    const text = $t.text()
                    expect(text).to.eq(message)
                })
            })
        })
    })
})
