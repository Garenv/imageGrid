describe('user visits the registration page',  () => {
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

    let attemptRegistration = function (username, email, password, confirmPassword, agreementCheck) {
        cy.clearType(usernameSelector, username)
        cy.clearType(emailSelector, email)
        cy.clearType(passwordSelector, password)
        cy.clearType(confirmPasswordSelector, confirmPassword)
        if(agreementCheck) {
            cy.get(agreementCheckSelector).click()
        }
        return cy.get('.card-back > .center-wrap > .section > .btn').click()
    };

    let name = "FakeUsername";
    let password = "NotARealPassword@1234";
    let email = "ValidUsername@gmail.com";

    describe('user attempts to register', {testIsolation: false}, () => {
        let pleaseFillOut = 'Please fill out this field.';

        beforeEach(() => {
            cy.session("sign up page", () => {
                cy.visit('/login')
                cy.get('[for="reg-log"]').click()
            }, {validate() {
                    cy.get(usernameSelector).clear()
                }})
        });

        describe('with invalid inputs', () => {
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

        describe('with valid credentials', () => {
            let webhookToken = ""
            let newEmail = ""
            let webhookPath = "cypress/fixtures/local/webhookSite.json";

            before(() => {
                cy.readFile(webhookPath)
                    .then((f) => {
                        let expirationTime;
                        if (f == null) {
                            expirationTime = new Date().getTime();
                            f = {
                                uuid: "",
                                expires_at: "",
                                domain: "@email.webhook.site"
                            };
                        } else {
                            expirationTime = new Date(f['expires_at']).getTime();
                        }

                        let currentTime = new Date().getTime();
                        if(expirationTime > currentTime) return f;
                        return cy.request({
                            method: 'POST',
                            url: 'https://webhook.site/token',
                            body: {
                                "default_status": 200,
                                "default_content": "Hello world!",
                                "default_content_type": "text/plain",
                                "timeout": 0,
                                "cors": false,
                                "expiry": 604800,
                                "alias": "my-webhook",
                                "actions": true
                            }
                        }).then(response => {
                            expect(response.status).eq(201);
                            f['uuid'] = response.body['uuid'];
                            f['expires_at'] = response.body['expires_at'];
                            return cy.writeFile(webhookPath, JSON.stringify(f));
                        });
                    })
                    .then(updatedF => {
                        webhookToken = updatedF['uuid'];
                        newEmail = webhookToken + updatedF['domain'];
                        console.log(webhookToken);
                    });

                cy.request({method:'DELETE', url:'/api/deleteAllUsers', failOnStatusCode: false});
            });

            it("should redirect to confirm email page", () => {
                attemptRegistration("Webhook", newEmail, password, password, true)
                cy.location("pathname").should("eq", "/email/verify");
                cy.request({
                    method: 'GET',
                    headers: { accept: 'application/json' },
                    url: `https://webhook.site/token/${webhookToken}/requests?sorting=newest/`
                }).then((response) => {
                    expect(response.status).eq(200)
                    let c = response.body.data[0]
                    let h = c["headers"]
                    expect(h["to"][0]).eq(newEmail)
                    expect(h["subject"][0]).eq('Verify Email Address')
                    expect(h["from"][0]).eq('Phopixel <contactSupport@phopixel.com>')

                    let emailBody = c["text_content"]
                    console.log(emailBody)

                    const urlRegex = /(?:https?|ftp):\/\/[^\s\r\n]+/g;
                    const extractedUrls = emailBody.match(urlRegex);
                    let link = extractedUrls[0].replace('\\r\\n\\r\\nIf', '')

                    cy.visit(link)
                    cy.location("pathname").should("eq", "/grid");
                })
            })
        });
    })

    describe('user attempts to register with invalid', () => {
        beforeEach(() => {
            cy.visit('/login')
            cy.get('[for="reg-log"]').click()
        })

        describe('permissions', () => {
            before(() => {
                cy.createUser(name, email, password)
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

        describe('credentials', () => {
            before(() => {
                cy.request({method:'DELETE', url:'/api/deleteAllUsers', failOnStatusCode: false});
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
})
