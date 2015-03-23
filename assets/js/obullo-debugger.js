 function debuggerShowTab(elem,target)
 {

 	var containers = document.getElementsByClassName('obulloDebugger-container');
		for (var i=0; i < containers.length;i+=1)
		{
		 	containers[i].style.display = 'none';
		};

 	var activeTabLinks = document.getElementsByClassName('obulloDebugger-activeTab');
		for (var i=0; i < activeTabLinks.length;i+=1)
		{
		 	activeTabLinks[i].classList.remove("obulloDebugger-activeTab");
		};

	var targetContainer = document.getElementById(target);
		targetContainer.style.display = "block";
 		elem.className = 'obulloDebugger-activeTab';

 };


 function hideDebugger()
 {
 	var obulloDebugger = document.getElementById('obulloDebugger');
 		obulloDebugger.style.display = "none";
 }

 document.onkeydown = function(key){
 	var press = key.keyCode;

 	if (press == 120)
	{
		var obulloDebugger = document.getElementById('obulloDebugger');
		    obulloDebugger.style.display = (obulloDebugger.style.display == 'none') ? 'block' : 'none';
	};
 };


 function fireMiniTab(elem)
 {

 	var target  = elem.getAttribute('data_target');
 	var element = document.getElementById(target);

 	if(elem.classList.contains('activeMiniTab') == true)
 	{
 		elem.classList.remove('activeMiniTab')
 		element.style.display = ''; 
 	}
 	else
 	{
 		elem.className =  elem.className + ' activeMiniTab';
 		element.style.display = 'block'; 
 	}

 };