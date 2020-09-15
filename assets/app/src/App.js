import * as React from 'react';
import { Voyager } from "graphql-voyager"
import "whatwg-fetch"

/**
 * Style the app
 */
import './app.css';

const parameters = {}

window.location.search
	.substr(1)
	.split(`&`)
	.forEach(function (entry) {
		var eq = entry.indexOf(`=`)
		if (eq >= 0) {
			parameters[decodeURIComponent(entry.slice(0, eq))] = decodeURIComponent(entry.slice(eq + 1).replace(/\+/g, '%20'))
		}
	})

// Derive a fetch URL from the current URL, sans the GraphQL parameters.
const graphqlParamNames = {
	query: true,
	variables: true,
	operationName: true,
	explorerIsOpen: true,
}

const otherParams = {}

for (var k in parameters) {
	if (parameters.hasOwnProperty(k) && graphqlParamNames[k] !== true) {
		otherParams[k] = parameters[k]
	}
}

const nonce = (window.wpGraphQLVoyagerSettings && window.wpGraphQLVoyagerSettings.nonce) ? window.wpGraphQLVoyagerSettings.nonce : null;
const endpoint = (window.wpGraphQLVoyagerSettings && window.wpGraphQLVoyagerSettings.graphqlEndpoint) ? window.wpGraphQLVoyagerSettings.graphqlEndpoint : window.location.origin;


function graphQLFetcher(query) {
	return fetch(endpoint, {
		method: `post`,
		headers: {
			Accept: `application/json`,
			"Content-Type": `application/json`,
			'X-WP-Nonce': nonce
		},
		body: JSON.stringify({ query }),
		credentials: `include`,
	}).then(resp => resp.json())
}

function App() {
	return (
		<Voyager introspection={graphQLFetcher}></Voyager>
	)
}

export default App;
