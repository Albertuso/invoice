it('Select Enterprise, New Product', () => {
        cy.visit('http://www.invoice.tld/');
        cy.get('[href="/login"]').click();
        cy.get('#inputUsername').type('invoiceUser');
        cy.get('#inputPassword').type('pcw12345');
        cy.get('.btn').click({force:true});
       
        // Select Enterprise
        cy.get('.card-body > a').click();
        cy.get('body > nav > .collapse > ul > :nth-child(2)').click();

        // Create new product
        cy.fixture('dataInvoice').then((fixedData) => {
            cy.get('#tableArea > a').click();
            cy.get('#product_name').type(fixedData.product);
            cy.get('#product_price').type(fixedData.price);
            cy.get('#product_vat').type(fixedData.vat);
            cy.get('#product_description').type(fixedData.description);

            cy.get('.btn').click({ force:true });
        })
        
        // Edit product
        cy.get('tbody > tr > :nth-child(5) > :nth-child(2)').click();
        
        cy.fixture('dataInvoice').then((fixedData) => {
            cy.get('#product_price').should('have.value', fixedData.price)
            .clear()
            .type(fixedData.priceedit);
        })
        cy.get('[name="product"] > .btn').click();

        // Delete product
        cy.visit('http://www.invoice.tld/');
        cy.get('.card-body > a').click();
        cy.get('body > nav > .collapse > ul > :nth-child(2)').click();
        
        // CAMBIAR LA FORMA DE COGER EL BOTON EDIT, SINO SOLO FUNCIONA PARA INTRODUCIR 1 PRODUCTO
        cy.get('#tableArea > table > tbody > tr > td:nth-child(5) > a:nth-child(2)').click();
        cy.get('body > div.container > div > div > form:nth-child(3) > button').click({force:true});

        cy.visit('http://www.invoice.tld/');
        cy.get('.card-body > a').click();
        cy.get('body > nav > .collapse > ul > :nth-child(2)').click();
        
        
    });