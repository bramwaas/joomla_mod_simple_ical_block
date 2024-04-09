/**
 * simple-ical-block-view.js
 * view simple-ical-block output with extra client parameter tzid_ui using Ajax 
 * restRoot for endpoint passed via inlinescript and this script in enqueue_block_assets 
 * v2.4.0
**/
const endpoint = window.simpleIcalBlock.restRoot + "&module=simple_ical_block&method=get&format=json";
let titl;

window.simpleIcalBlock = {...(window.simpleIcalBlock || {}), ...{
	fetchFromRest: function(dobj, ni) {
/*		fetch(endpoint, { */
        let epg = endpoint + '&sibid=' + dobj.sibid + '&tzid_ui=' + dobj.tzid_ui + '&Itemid=' + dobj.Itemid;
        console.log(epg);
		fetch(epg, {
/*			method: "POST", */
			method: "GET",
			cache: "no-cache", // *default, no-cache, reload, force-cache, only-if-cached
			headers: { "Content-Type": "application/json", },
/*			body: JSON.stringify(dobj), */
		}).then((response) => {
			if (!response.ok) {
				throw new Error(`HTTP error, status = ${response.status}`);
			}
			return response.json();
		}).then((res) => {
			ni.setAttribute('data-sib-st', 'completed');
/*			if (ni.getAttribute('data-sib-notitle')) titl = ''; else titl = ni.querySelector( '[data-sib-t="true"]' ).outerHTML; 
			ni.innerHTML = titl + res.content; */
			ni.innerHTML = res.data.content;
			console.log(res);
		}
		).catch((error) => {
			console.log(error);
			ni.setAttribute('data-sib-st', 'Error :' + error.code + ':' + error.message);
			ni.innerHTML = '<p>= Code: ' + error.code + '<br>= Msg: ' + error.message + '</p>';
		})
	}
	,
	getBlockByIds: function() {
		const nodeList = document.querySelectorAll('[data-sib-st]');
		const ptzid_ui = Intl.DateTimeFormat().resolvedOptions().timeZone;
		let paramsObj = {"wptype": "REST", "tzid_ui":ptzid_ui};
		for (let i = 0; i < nodeList.length; i++) {
			paramsObj.sibid = nodeList[i].getAttribute('data-sib-id');
			paramsObj.Itemid = nodeList[i].getAttribute('data-sib-maid');
			nodeList[i].setAttribute('data-sib-st', 'f1');
			this.fetchFromRest(paramsObj, nodeList[i]);
		}
	}
}
}
window.simpleIcalBlock.getBlockByIds();
