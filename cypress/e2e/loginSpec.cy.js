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

            it(`should fail with ${message}`, () => {
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

    describe("user attempts to login with valid credentials", () => {
        // let email = "testeraiprototype@gmai.com"
        let email = "philosopherlife@yahoo.com"
        let password = "alex1234"
        // let password = "TesterAIPrototype1!"
        let settingsSelector = '[autofocus=""]'
        let avatarIconSelector = '.MuiAvatar-root'
        let profileSelector = '.css-1hf8zo6 > .MuiPaper-root > .MuiList-root > :nth-child(3)'
        let logoutSelector ='.myButton > .MuiButtonBase-root'
        let uploadSelector = '.custom-file-upload > .btn'
        function waitFor200(routeAlias, retries=2) {
            cy.wait(routeAlias).then(xhr => {
                if (xhr.response.statusCode === 200) return // OK
                else if (retries > 0) waitFor200(routeAlias, retries - 1); // wait for the next response
                else throw "All requests returned non-200 response";
            })
        }
        it.only(`should redirect to the grid`, () => {
            cy.intercept('/get-avatar-image').as("get-avatar-image")
            cy.intercept('/check-session').as("check-session")
            cy.intercept('/get-user-uploads-data').as("get-user-uploads-data")
            attemptLogin(email, password)
            cy.location('pathname').should('eq', '/grid')
            cy.contains('Upload').should('be.visible')
            waitFor200('@get-avatar-image')
            waitFor200('@check-session')
            waitFor200('@get-user-uploads-data')
            cy.get(avatarIconSelector).click()
            cy.get(profileSelector).click()
        })
    })
})
