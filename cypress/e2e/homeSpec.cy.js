
describe("user visits the home page", () => {
    let leftTitleSelector = '.logo > a'
    let description1Selector = "description-1"
    let description2Selector = "description-2"
    let createAccountIconSelector = "create-account-icon"
    let imageGridIconSelector = "image-grid-icon"
    let uploadPhotoIcon= "upload-photo-icon"
    let gainLikesIcon= "gain-likes-icon"
    let winPrizeIcon= "win-prize-icon"
    let faqFooterLink = 'faq-footer-link'
    let termsFooterLink = 'terms-footer-link'
    let privacyFooterLink = 'privacy-footer-link'
    let faqEndpoint = '/faq'
    let contactUsEndpoint = '/contact-us'

    beforeEach(() => {
        cy.visit("/")
    });

    it("should have expected text", () => {
        cy.isText(leftTitleSelector, "Phopixel")
        cy.isText(description1Selector, "Win prizes for uploading appealing photos")
        cy.isText(description2Selector, "The first website ever to award prizes contingent on how well-liked photos are")

        cy.containsText(createAccountIconSelector, "Create Account")
        cy.containsText(imageGridIconSelector, "Image Grid")
        cy.containsText(uploadPhotoIcon, "Upload Photo")
        cy.containsText(gainLikesIcon, "Gain Likes")
        cy.containsText(winPrizeIcon, "Win Prize")

        cy.containsText("phopixel-description", "\n                    What is Phopixel?\n                    \n                        Phopixel is the first website that ensures a fair and amiable opportunity for all to win prizes, determined entirely by the attractiveness of the photos you upload.\n                        The goal of Phopixel is to give something back to the world in a fair, concise and honest way\n                        \n                        \n                        Here are a few things to note:\n                    \n                    \n                        Available worldwide\n                        It's free to join and participate\n                        Prizes are given out weekly to the top 3 like amounts for that week\n                        Inappropriate photos aren't allowed and will automatically be rejected by the system upon uploading\n                        You may only upload one photo per week\n                        Visit the FAQ section for more info!\n                    \n\n                ")

        cy.get("about-navbar").click()
        cy.isVisibleWithText("phopixel-description", "\n                    What is Phopixel?\n                    \n                        Phopixel is the first website that ensures a fair and amiable opportunity for all to win prizes, determined entirely by the attractiveness of the photos you upload.\n                        The goal of Phopixel is to give something back to the world in a fair, concise and honest way\n                        \n                        \n                        Here are a few things to note:\n                    \n                    \n                        Available worldwide\n                        It's free to join and participate\n                        Prizes are given out weekly to the top 3 like amounts for that week\n                        Inappropriate photos aren't allowed and will automatically be rejected by the system upon uploading\n                        You may only upload one photo per week\n                        Visit the FAQ section for more info!\n                    \n\n                ")

        cy.get("prizes-navbar").click()
        cy.isVisibleWithText("engineering-description", "\n                    \n                        \n                        Integrity\n                        We utilize both AI and ML, specifically through deep learning models, to analyze and identify elements within images to ensure nothing inappropriate gets uploaded ranging from thirst trap photos to violence to hate and much more\n                    \n                    \n                        \n                        User Experience\n                        Engineered on top of AWS which means your experience will be nothing but smooth and seamless\n                    \n                    \n                        \n                        System\n                        We're continuously upgrading our systems and adding new features.  Feel free to contact us if you have any suggestions!\n                    \n                ")
        cy.isVisibleWithText("engineering-description2", '\n                        \n                        User Experience\n                        Engineered on top of AWS which means your experience will be nothing but smooth and seamless\n                    ')
        cy.isVisibleWithText("engineering-description3", "\n                        \n                        System\n                        We're continuously upgrading our systems and adding new features.  Feel free to contact us if you have any suggestions!\n                    ")

        cy.isVisibleWithText('prizes-text', "Here are some of the many prizes you could win!")
        cy.isVisibleWithText(faqFooterLink, "FAQ")
        cy.isVisibleWithText(termsFooterLink, "Terms & Conditions")
        cy.isVisibleWithText(privacyFooterLink, "Privacy Policy")

        let year = new Date().getFullYear()
        cy.isVisibleWithText("copyright-text", `\n             Copyright © ${year} Phopixel\n            \n            All Rights Reserved.\n        `)
    });

    let testLink = function (selector, endpoint) {
        context(`user clicks ${selector}`, () => {
            it(`should go to ${endpoint} page`, () => {
                cy.get(selector).click();
                cy.location("pathname").should('eq', endpoint);
            });
        });
    };

    describe("user clicks links", () => {
        context("description", () => {
            [
                ["faq-description-link", faqEndpoint],
                ["contact-us-description-link", contactUsEndpoint]
            ].forEach(([selector, endpoint]) => {
                testLink(selector, endpoint)
            });
        });

        context("navbar", () => {
            [
                [createAccountIconSelector, "/login"],
                ["contact-us-navbar", contactUsEndpoint],
                ["faq-navbar", faqEndpoint],
                ["about-us-navbar", "/login"]
            ].forEach(([selector, endpoint]) => {
                testLink(selector, endpoint)
            });
        });


        context("footer", () => {
            [
                [faqFooterLink, faqEndpoint],
                [termsFooterLink, "/terms-and-conditions"],
                [privacyFooterLink, "/privacy-policy"]
            ].forEach(([selector, endpoint]) => {
                testLink(selector, endpoint)
            });
        });
    });



    context(`user is logged in`, () => {
        before(() => {
           cy.login()
        });
    });
})
