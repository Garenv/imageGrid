describe('user visits the registration page', () => {
    let usernameSelector = '#logname'
    let emailSelector = ':nth-child(3) > #email'
    let passwordSelector = ':nth-child(4) > #password'
    let confirmPasswordSelector = '#password-confirmation'
    let agreementCheckSelector = '#agreementCheck'

    before(() => {
        Cypress.on('uncaught:exception', (err, runnable) => {
            return false
        })

    })
    beforeEach(() => {
        cy.visit('/login')
        cy.get('[for="reg-log"]').click()
    })

    let attemptRegistration = function (username, email, password, confirmPassword, agreementCheck) {
        if(username) {
            cy.get(usernameSelector).type(username)
        }
        if(email) {
            cy.get(emailSelector).type(email)
        }
        if (password) {
            cy.get(passwordSelector).type(password)
        }
        if(confirmPassword) {
            cy.get(confirmPasswordSelector).type(confirmPassword)
        }
        if(agreementCheck) {
            cy.get(agreementCheckSelector).click()
        }
        return cy.get('.card-back > .center-wrap > .section > .btn').click()
    };

    let name = "FakeUsername";
    let password = "NotARealPassword@1234";
    let email = "ValidUsername@gmail.com";

    describe('user attempts to register with invalid inputs', () => {
        let pleaseFillOut = 'Please fill out this field.';

        [
            ["FakeUsername", "ValidUsername.com", "NotARealPassword1234", "NotARealPassword1234", false, "Please include an '@' in the email address. 'ValidUsername.com' is missing an '@'.", emailSelector],
            ["FakeUsername", "ValidUsername@", "NotARealPassword1234", "NotARealPassword1234", false, "Please enter a part following '@'. 'ValidUsername@' is incomplete.", emailSelector],
            ["", "ValidUsername@", "NotARealPassword1234", "NotARealPassword1234", false, pleaseFillOut, usernameSelector],
            ["FakeUsername", "", "NotARealPassword1234", "NotARealPassword1234", false, pleaseFillOut, emailSelector],
            ["FakeUsername", email, "", "NotARealPassword1234", false, pleaseFillOut, passwordSelector],
            ["FakeUsername", email, "NotARealPassword1234", "", false, pleaseFillOut, confirmPasswordSelector],
            ["", "", "", "", false, pleaseFillOut, usernameSelector],
            ["FakeUsername", email, "NotARealPassword1234", "NotARealPassword1234", false, 'Please check this box if you want to proceed.', agreementCheckSelector],
        ].forEach((value, index, array) => {
            let username = value[0]
            let email = value[1]
            let password = value[2]
            let confirmPassword = value[3]
            let agreementCheck = value[4]
            let message = value[5]
            let selector = value[6]

            it(`${index + 1}. should fail with ${message}`, () => {
                attemptRegistration(username, email, password, confirmPassword, agreementCheck)
                cy.get(selector).then(($t) => {
                    const text = $t[0].validationMessage
                    expect(text).to.eq(message)
                })
            })
        })
    })

    describe('user attempts to register with valid credentials', () => {
        before(() => {

        });

        it("should redirect to confirm email page", () => {
            let chainable = attemptRegistration(name, email, password, password, true)
        })
    });

    describe('user attempts to register with invalid credentials', () => {
        before(() => {

        });

        [
            [name, email, password, password, true, 'You may not create multiple accounts in order to gain an unfair advantage by uploading additional photos.✖'],
            [name, email, "notreal@1", "notreal@1", true, "The password must contain at least one uppercase letter, one lowercase letter, one number, and one special character.✖The password must be at least 10 characters.✖"],
            [name, email, "NotReal!1", "NotReal!1", true, "The password must be at least 10 characters.✖"],
            ["Fuck", email, password, password, true, "Names may not contain any profanity✖"],
            [name, email, password, "NotARealPassword", true, 'The password confirmation does not match.✖'],
            [name, email, "NotARealPassword1234", "NotARealPassword", true, 'The password must contain at least one uppercase letter, one lowercase letter, one number, and one special character.✖The password confirmation does not match.✖'],
        ].forEach((value, index, array) => {
            let username = value[0]
            let email = value[1]
            let password = value[2]
            let confirmPassword = value[3]
            let agreementCheck = value[4]
            let message = value[5]

            it(`should fail with ${message}`, () => {
                attemptRegistration(username, email, password, confirmPassword, agreementCheck)
                cy.get('.toastify').then(($t) => {
                    const text = $t.text()
                    expect(text).to.eq(message)
                })
            })
        })
    })
})
