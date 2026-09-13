function openTab(evt, tabName) {
	// Declare all variables
	var i, tabcontent, tablinks

	// Get all elements with class="tabcontent" and hide them
	tabcontent = document.getElementsByClassName('ax_tabcontent')
	for (i = 0; i < tabcontent.length; i++) {
		tabcontent[i].style.display = 'none'
	}

	// Get all elements with class="tablinks" and remove the class "active"
	tablinks = document.getElementsByClassName('ax_tablinks')
	for (i = 0; i < tablinks.length; i++) {
		tablinks[i].className = tablinks[i].className.replace(' active', '')
	}

	// Show the current tab, and add an "active" class to the button that opened the tab
	document.getElementById(tabName).style.display = 'flex'
	evt.currentTarget.className += ' active'
}

const info = document.createElement('div')
info.id = 'ax_info'

const copyright = document.createElement('span')
copyright.id = 'ax_copyright'
copyright.innerHTML =
	"<svg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 20 20' fill='currentColor'><path fillRule='evenodd' d='M11.3 1.046A1 1 0 0112 2v5h4a1 1 0 01.82 1.573l-7 10A1 1 0 018 18v-5H4a1 1 0 01-.82-1.573l7-10a1 1 0 011.12-.38z' clipRule='evenodd' /></svg> توسعه یافته توسط <a href='https://www.zhaket.com/store/web/mojtabakh'>AxiosIO</a><br/><br />کپی رایت " + new Date().getFullYear() + '&copy;<br/>'

info.appendChild(copyright)

const softwareInfo = document.createElement('span')
softwareInfo.id = 'ax_software_info'

info.append(softwareInfo)

const panel = document.querySelector('aside')
if(panel) panel.appendChild(info)

const overlays = document.querySelectorAll('.ax_tabcontent .overlay');
const reloadedOptions = [
	document.querySelector(".ax_tabcontent #trust_forms_version"),
	document.querySelector(".ax_tabcontent #active_sms_tool")
];

reloadedOptions.forEach((item, index) => {
	item.addEventListener('change', e => {
		overlays[index].style.display = "block";
	});
});