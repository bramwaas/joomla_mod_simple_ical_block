/**
 * simple-ical-block-view.js
 * view simple-ical-block output with extra client parameter tzid_ui using Ajax 
 * restRoot for endpoint passed via inlinescript and this script in enqueue_block_assets 
 * v2.4.0
**/
const endpoint = window.simpleIcalBlock.restRoot + "&module=simple_ical_block&method=get&format=json";
let titl,epg;

window.simpleIcalBlock = {...(window.simpleIcalBlock || {}), ...{
	fetchFromRest: function(dobj, ni) {
        epg = endpoint + '&sibid=' + dobj.sibid + '&tzid_ui=' + dobj.tzid_ui;
		fetch(epg, {
			method: "GET",
			cache: "no-cache", // *default, no-cache, reload, force-cache, only-if-cached
			headers: { "Content-Type": "application/json", },
		}).then((response) => {
			if (!response.ok) {
				throw new Error(`HTTP error, status = ${response.status}`);
			}
			return response.json();
		}).then((res) => {
			ni.setAttribute('data-sib-st', 'completed');
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
			nodeList[i].setAttribute('data-sib-st', 'f1');
			this.fetchFromRest(paramsObj, nodeList[i]);
		}
	}
}
}
window.simpleIcalBlock.getBlockByIds();
