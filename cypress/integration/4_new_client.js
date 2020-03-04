it('Select Enterprise, New Client', () => {
    cy.visit('http://www.invoice.tld/');
    cy.get('[href="/login"]').click();
    cy.get('#inputUsername').type('invoiceUser');
    cy.get('#inputPassword').type('pcw12345');
    cy.get('.btn').click({force:true});

    // Select Enterprise
    cy.get('.card-body > a').click();
    // Select Client
    cy.get(':nth-child(1) > :nth-child(1) > .nav-link').click();
    // Create new client
    cy.get('#tableArea > a').click();
    cy.fixture('dataInvoice').then((fixedData) => {
        cy.get('#client_name').type(fixedData.cliente);
        cy.get('#client_address').type(fixedData.addressc);
        cy.get('#client_nif').type(fixedData.dni);
        cy.get('#client_email').type(fixedData.emailc);
        cy.get('#client_telephone').type(fixedData.telefono);
        cy.get('#client_web').type(fixedData.webc);
    })

    // Save client
    cy.get('.btn').click({ force:true });

    // Edit client
    cy.get('#tableArea > table > tbody > tr > td:nth-child(8) > a:nth-child(2)').click();
    cy.fixture('dataInvoice').then((fixedData) => {
        cy.get('#client_email').should('have.value', fixedData.emailc)
        .clear()
        .type(fixedData.emailedit);
    })
    cy.get('[name="client"] > .btn').click();
    
    
    // Delete client
    cy.get('#tableArea > table > tbody > tr > td:nth-child(8) > a:nth-child(2)').click();
    cy.get('body > div.container > form > button').click({force:true});

    cy.visit('http://www.invoice.tld/');
    cy.get('body > div.container.mt-2 > div > div > div > div:nth-child(1) > div.card-body.text-center > a').click();
    cy.get('#navbarNavDropdown > ul:nth-child(1) > li:nth-child(1) > a').click();
    
    
});