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
        cy.visit('/login')
        cy.get('[for="reg-log"]').click()
        cy.get('.card-back > .center-wrap > .section > .mb-4').then((element) => {
            expect(element.text()).eq("Sign Up")
        })
    })

    beforeEach(() => {

    })

    let clearType = function (selector, text) {
        cy.get(selector).clear()
        if(text) {
            cy.get(selector).clear().type(text)
        }
    };

    let attemptRegistration = function (username, email, password, confirmPassword, agreementCheck) {
        clearType(usernameSelector, username)
        clearType(emailSelector, email)
        clearType(passwordSelector, password)
        clearType(confirmPasswordSelector, confirmPassword)
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
        before(() => {
            cy.visit('/login')
            cy.get('[for="reg-log"]').click()
            cy.get('.card-back > .center-wrap > .section > .mb-4').then((element) => {
                expect(element.text()).eq("Sign Up")
            })
        });

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
        let webhookToken = "e48d4421-412e-4e65-8218-9f617e261870"
        let email = webhookToken + '@email.webhook.site'

        before(() => {
            cy.request({method:'DELETE', url:'/api/deleteAllUsers', failOnStatusCode: false});
            cy.request({method: 'POST', url: 'https://webhook.site/token', body: {
                    "default_status": 200,
                    "default_content": "Hello world!",
                    "default_content_type": "text/html",
                    "timeout": 0,
                    "cors": false,
                    "expiry": 604800,
                    "alias": "my-webhook",
                    "actions": true
                }
            })
        });

        it.only("should redirect to confirm email page", () => {
            attemptRegistration(name, email, password, password, true)
            cy.location("pathname").should("eq", "/email/verify");
            cy.request({
                method: 'GET',
                headers: { accept: 'application/json' },
                url: `https://webhook.site/token/${webhookToken}/requests?sorting=newest/`
            }).then((response) => {
                expect(response.status).eq(200)
                let c = response.body.data[0]
                // let h = response.headers
                // expect(h["to"]).eq(email)
                // expect(h["subject"]).eq('Verify Email Address')
                // expect(h["from"]).eq('Phopixel <contactSupport@phopixel.com>')
                // expect(h["date"]).eq('Wed, 10 Jan 2024 13:03:32 +0000')
                let emailBody = c["text_content"]
                const urlRegex = /(?:https?|ftp):\/\/[^\s\r\n]+/g;
                const extractedUrls = emailBody.match(urlRegex);
                let firstLink = extractedUrls[0].replace('\\r\\n\\r\\nIf', '')
                let secondLink = extractedUrls[1].replace('\\r\\n\\r\\n©', '')
                cy.visit(firstLink)
                cy.location("pathname").should("eq", "/grid");
                // cy.wrap(body).each((c) => {
                //
                //     // expect(c["text_content"]).eq(`Phopixel\r\n\r\n# Hello!\r\n\r\nPlease click the button below to verify your email address.\r\n\r\nVerify Email Address: ${firstLink}\r\n\r\nIf you did not create an account, no further action is required.\r\n\r\nRegards,\r\nPhopixel\r\n\r\nIf you're having trouble clicking the "Verify Email Address" button, copy and paste the URL below\r\ninto your web browser: ${secondLink}\r\n\r\n© 2024 Phopixel. All rights reserved.\r\n`)
                //     // let method = c['method']
                //     // let content = c['content']
                //     // let date = c['created_at']
                //     // expect(content).eq("")
                //     // expect(method).eq("")
                //     // expect(date).eq("")
                // })
            })
        })
    });

    describe('user attempts to register with invalid permissions', () => {
        before(() => {
            const newUser = {
                name: name,
                email: email,
                registerPassword: password,
                registerPassword_confirmation: password,
                skipMultipleAccounts: true,
                email_verified_at: "2023-12-29 21:53:59"
            };

            cy.request({method:'POST', url:'/api/createUser', body:newUser, failOnStatusCode: false})
                .then((registerResponse) => {
                if(registerResponse.status !== 200) {
                    // expect(registerResponse.body.errors).to.equal(200);
                }
            });
        });

        beforeEach(() => {
            cy.visit('/login')
            cy.get('[for="reg-log"]').click()
        });

        [
            [name, email, password, password, true, 'The email has already been taken.✖This name already exists, choose another one✖'],
            ["NewAccount", email, password, password, true, 'The email has already been taken.✖'],
            ["NewAccount", "NewEmail@gmail.com", password, password, true, 'You may not create multiple accounts in order to gain an unfair advantage by uploading additional photos.✖']
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

    describe('user attempts to register with invalid credentials', () => {
        before(() => {
            cy.request({method:'DELETE', url:'/api/deleteAllUsers', failOnStatusCode: false});
        })

        beforeEach(() => {
            cy.visit('/login')
            cy.get('[for="reg-log"]').click()
        });

        [
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
