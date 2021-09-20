"use strict";


/*
 * ref:
 *   supertest: https://github.com/visionmedia/supertest
 *   mocha: https://mochajs.org/
 *   test: npx mocha test/server.api.test.js 
 */

let request  = require('supertest');
const assert = require('assert').strict;



/*
 * Configuration
 */
 const VERBOSE = false
 request       = request('http://localhost/api')


/**
 * Global var
 */

/*
 * Route: Monitor
 */  
describe('API: ', function() {

    it('Get all orders', function(done) {
        request
            .get('/orders')
            .set('Content-Type', 'application/json')
            .set('Accept', 'application/json')
            .expect('Content-Type', /json/)
            .expect(200)
            .then(response => {
                done();
                if( VERBOSE ) console.table([response.status, response.type])
            })
            .catch(err => done(err))
     })

     it('Get order id: 1', function(done) {
        request
            .get('/order/1')
            .set('Content-Type', 'application/json')
            .set('Accept', 'application/json')
            .expect('Content-Type', /json/)
            .expect(200)
            .then(response => {
                done();
                if( VERBOSE ) console.table([response.status, response.type])
            })
            .catch(err => done(err))
     })

     it('Get all products', function(done) {
        request
            .get('/products')
            .set('Content-Type', 'application/json')
            .set('Accept', 'application/json')
            .expect('Content-Type', /json/)
            .expect(200)
            .then(response => {
                done();
                if( VERBOSE ) console.table([response.status, response.type])
            })
            .catch(err => done(err))
     })

     it('Get product sku: ref1', function(done) {
        request
            .get('/product/ref1')
            .set('Content-Type', 'application/json')
            .set('Accept', 'application/json')
            .expect('Content-Type', /json/)
            .expect(200)
            .then(response => {
                done();
                if( VERBOSE ) console.table([response.status, response.type])
            })
            .catch(err => done(err))
     })


})




