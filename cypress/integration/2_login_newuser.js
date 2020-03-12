// context('Login New User', () => {

// 	it('Login New User, Create Enterprise', () => {
//             cy.visit('http://www.invoice.tld/');
//             cy.get('[href="/login"]').click();
//             cy.get('#inputUsername').type('invoiceUser');
//             cy.get('#inputPassword').type('pcw12345');
//             cy.get('.btn').click({force:true});
//             // Create Enterprise
            
//             cy.get(':nth-child(1) > .card-body > .card-text > a > img').click({force:true});

//             cy.fixture('dataInvoice').then((fixedData) => {
//                     cy.get('#enterprise_name').type(fixedData.enterprise);
//                     cy.get('#enterprise_address').type(fixedData.address);
//                     cy.get('#enterprise_telephone').type(fixedData.telefono)
//                     cy.get('#enterprise_email').type(fixedData.email);
//                     cy.get('#enterprise_web').type(fixedData.web);
//                     cy.get('#enterprise_nif').type(fixedData.dni);
//                     cy.get('#enterprise_logo').click();
//                     cy.get('#enterprise_footer').type(fixedData.footer);

                   
//                     // cy.fixture('img/str.png').as('logo')
//                     // cy.get('input[type=file]').then(function($input) {
//                     // // convert the logo base64 string to a blob
//                     // return Cypress.Blob.base64StringToBlob(this.logo, 'image/png')
//                     //     .then((blob) => {
//                     //     // pass the blob to the fileupload jQuery plugin
//                     //     // used in your application's code
//                     //     // which initiates a programmatic upload
//                     //     $input.fileupload('add', { files: blob })
//                     //     })
//                     // })

//                     // cy.fixture('str.png', 'binary')
//                     // .then(Cypress.Blob.binaryStringToBlob)
//                     // .then(fileContent => {
//                     //     cy.get('#upload-video').upload({
//                     //     fileContent,
//                     //     fileName: 'str.png',
//                     //     mimeType: 'img/png',
//                     //     encoding: 'utf8'
//                     //     })
//                     // })



//                     cy.get('.btn').click({ force:true });
//             }) 

//             // Edit enterprise
//             cy.get('tbody > tr > :nth-child(9) > :nth-child(2)').click();
//             cy.fixture('dataInvoice').then((fixedData) => {
//                 cy.get('#enterprise_email').should('have.value', fixedData.email)
//                 .clear()
//                 .type(fixedData.emaile);
//             })
//             cy.get('body > :nth-child(3) > .btn').click({force:true});


//             // Delete enterprise
//             cy.get('tbody > tr > :nth-child(9) > :nth-child(2)').click();
//             cy.get('body > :nth-child(5) > .btn').click({force:true});

//             cy.visit('http://www.invoice.tld/enterprise/');



//     });


       
});