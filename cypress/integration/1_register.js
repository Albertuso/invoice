context('Register and Edit Max Enterprises', () => {

        it('Visit Invoice', () => {
                cy.visit('http://www.invoice.tld/');
        });

        it('Register', () => {
                cy.get('[href="/register"]').click();
        });
        it('Fill Register', () => {
                cy.fixture('dataInvoice').then((fixedData) => {
                        cy.get('#registration_form_username').type(fixedData.username)
                        cy.get('#registration_form_plainPassword').type(fixedData.password)
                        cy.get('#registration_form_agreeTerms').click();
                        // REALIZAR REGISTRO
                        cy.get('.btn').click({ force: true });
                })
        });

        var admin = "admin";
        var pw = "pcw12345.";
        var nEnterprises = 5;

        it('Login Admin, Check New User, Edit and Check', () => {
                cy.visit('http://www.invoice.tld/login');
                cy.get('#inputUsername').type(admin);
                cy.get('#inputPassword').type(pw);
                cy.get('.btn').click();
                // Visitar vista admin
                cy.visit('http://www.invoice.tld/admin');
                // Listar Nuevo Usuario Creado
                cy.get('.title').contains('User').click();
                cy.fixture('dataInvoice').then((fixedData) => {
                        cy.contains(fixedData.username);
                });
                // Edit number of enterprises
                cy.contains('invoiceUser').parents('tr').find('.action-edit').click({ force: true });
                cy.get('#user_max').clear().type(nEnterprises);
                // Save
                cy.get('.action-save').click({ force: true });
                // Check number of enterprises
                cy.contains('invoiceUser').parents('tr').find('.integer').contains(nEnterprises);
        });

        it('LogOut Admin and Login New User', () => {
                cy.get('.user-name').click();
                cy.get('.user-action-logout').click({ force: true });

                // Login New User
                cy.get('[href="/login"]').click();
                cy.get('#inputUsername').type('invoiceUser');
                cy.get('#inputPassword').type('pcw12345');
                cy.get('.btn').click();
        });

});