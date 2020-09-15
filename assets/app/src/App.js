import * as React from 'react';
import { Voyager } from "graphql-voyager"
import "whatwg-fetch"

/**
 * Style the app
 */
import './app.css';
import 'graphql-voyager/dist/voyager.css'


const nonce = (window.wpGraphQLVoyagerSettings && window.wpGraphQLVoyagerSettings.nonce) ? window.wpGraphQLVoyagerSettings.nonce : null;
const endpoint = (window.wpGraphQLVoyagerSettings && window.wpGraphQLVoyagerSettings.graphqlEndpoint) ? window.wpGraphQLVoyagerSettings.graphqlEndpoint : window.location.origin;
// const endpoint = `http://swapi.apis.guru`


async function graphQLFetcher(query) {
	const resp = await fetch(endpoint, {
		method: `post`,
		headers: {
			Accept: `application/json`,
			"Content-Type": `application/json`,
			'X-WP-Nonce': nonce
		},
		body: JSON.stringify({ query }),
		credentials: `include`,
	})

	return resp.json()
}

function App() {
	return (
		<Voyager introspection={graphQLFetcher} displayOptions={{ skipDeprecated: false }} />
	)
}

export default App;
