describe('user visits the login page', { testIsolation: false }, () => {
    let emailSelector = '[data-cy="login-email-input"]'
    let passwordSelector = '[data-cy="login-password-input"]'

    beforeEach(() =>  {
        cy.session("login page",() => {
            cy.visit('/login')
        }, { validate() {
            cy.get(emailSelector).clear()
        }})
    })

    let attemptLogin = function (email, password) {
        cy.clearType(emailSelector, email)
        cy.clearType(passwordSelector, password)
        cy.get('[data-cy="login-button"]').click()
    };

    describe('user attempts to login with invalid inputs', () => {
        let pleaseFillOut = 'Please fill out this field.';

        [
            ["ValidUsername.com", "NotARealPassword1234", "Please include an '@' in the email address. 'ValidUsername.com' is missing an '@'.", emailSelector],
            ["ValidUsername@", "NotARealPassword1234", "Please enter a part following '@'. 'ValidUsername@' is incomplete.", emailSelector],
            ["", "", pleaseFillOut, emailSelector],
            ["", "NotARealPassword1234", pleaseFillOut, emailSelector],
            ["GoodEmail@gmail.com", "", pleaseFillOut, passwordSelector],
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

    describe("user has an account and attempts to login with", () => {
        let faker;

        before(() => {
            cy.fixture("users/Faker.json").then((json) => {
                faker = json;
                cy.createUser(faker.name, faker.email, faker.password);
            })
        });

        context("valid credentials", () => {
            let avatarIconSelector = '[data-testid="PersonIcon"]'
            let profileSelector = '.css-1t3k1b1-MuiModal-root-MuiPopover-root-MuiMenu-root > .MuiPaper-root > .MuiList-root > :nth-child(3)'
            let profileName = '[data-cy="profile-name"]'
            let profileEmail = '[data-cy="profile-email"]'
            function waitFor200(routeAlias, retries=2) {
                cy.wait(routeAlias).then(xhr => {
                    if (xhr.response.statusCode === 200) return
                    else if (retries > 0) waitFor200(routeAlias, retries - 1);
                    else throw "All requests returned non-200 response";
                })
            }
            it(`should redirect to the grid`, () => {
                cy.intercept('/get-avatar-image').as("get-avatar-image")
                cy.intercept('/check-session').as("check-session")
                cy.intercept('/get-user-uploads-data').as("get-user-uploads-data")
                attemptLogin(faker.email, faker.password)
                cy.location('pathname').should('eq', '/grid')
                cy.contains('Upload').should('be.visible')
                waitFor200('@get-avatar-image')
                waitFor200('@check-session')
                waitFor200('@get-user-uploads-data')
                cy.get(avatarIconSelector).click()
                cy.get(profileSelector).click()
                cy.get(profileName).then((element) => {
                    expect(element.text()).eq(faker.name)
                })
                cy.get(profileEmail).then((element) => {
                    expect(element.text()).eq(faker.email)
                })
            })
        })

        context('invalid credentials', () => {
                [
                    ["NotARealEmail@gmail.com", "NotARealPassword1234", 'These credentials do not match our records.✖'],
                    ["ValidUsername@gmail.com", "NotARealPassword1234", 'These credentials do not match our records.✖'],
                    // ["ValidUsername@gmail.com", "NotARealPassword1234", 'These credentials do not match our records.✖'],
                    // ["ValidUsername@gmail.com", "NotARealPassword1234", 'These credentials do not match our records.✖'],
                    // ["ValidUsername@gmail.com", "NotARealPassword1234", 'Your account has been locked due to too many attempts.✖']
                ].forEach(([username, password, message], index) => {
                it(`${index + 1}. should fail with ${message}`, () => {
                    attemptLogin(username, password)
                    cy.get('.toastify').then(($t) => {
                        const text = $t.text()
                        expect(text).to.eq(message)
                    })
                })
            })
        })
    })
})
