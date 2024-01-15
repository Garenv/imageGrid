describe('User Profile Test', () => {
    it('should create a user, log in, visit the grid page, and assert profile', () => {
        // Step 1: Create a user
        const newUser = {
            name: 'John Alex',
            email: 'john.Alex@yahoo.com',
            registerPassword: 'Password123!$',
            registerPassword_confirmation: 'Password123!$',
        };

        cy.request('POST', '/api/createValidUser', newUser).then((registerResponse) => {
            expect(registerResponse.status).to.equal(200);
            expect(registerResponse.body).to.equal("");
            // Step 2: Log in with the created user
            const loginData = {
                email: newUser.email,
                password: newUser.password,
            };

            cy.request('POST', '/login', loginData).then((loginResponse) => {
                expect(loginResponse.status).to.equal(200);

                // Step 3: Visit the grid page (Assuming '/grid' is the URL for the grid page)
                cy.visit('/grid');

                // Step 4: Assert profile matches the logged-in user
                cy.get('[data-cy=profile-name]').should('contain', newUser.name);
                cy.get('[data-cy=profile-email]').should('contain', newUser.email);
            });
        });
    });
});
