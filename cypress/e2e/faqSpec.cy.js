// describe("user visits the faq page", () => {
//
//     before(() => {
//         cy.visit("/faq")
//     })
//
//
//     it("should have the following text", () => {
//         cy.isVisibleWithText("faq-header", '\n            FAQ\n        ')
//         cy.request({ method: "GET", url: "/api/getFaq" }).then((response) => {
//             let f = response.body["faq"] ?? []
//             f.forEach((item, index) => cy.checkDetails(`details-${index}`, item));
//         })
//     })
// })
