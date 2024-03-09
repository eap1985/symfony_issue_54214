export function initCompany (){
console.log(123);
document.addEventListener('DOMContentLoaded', function () { // Аналог $(dPocument).ready(function(){

});

    const addFormToCollection = (e) => {
      
      
      const collectionHolder = document.querySelector('.' + e.target.dataset.collectionHolderClass);
    
      const item = document.createElement('li');
    
      item.innerHTML = collectionHolder
        .dataset
        .prototype
        .replace(
          /__name__/g,
          collectionHolder.dataset.index
        );
    
      collectionHolder.appendChild(item);
    
      collectionHolder.dataset.index++;
    
          // add a delete link to the new form
        addTagFormDeleteLink(item);
    };
    
    
    
    
    /*document
      .querySelectorAll('.add_item_link')
      .forEach(btn => {
          btn.addEventListener("click", addFormToCollection)
      });
      */
    
    const addTagFormDeleteLink = (item) => {
        const removeFormButton = document.createElement('button');
        removeFormButton.innerText = 'Delete this tag';
        removeFormButton.className = 'btn btn-primary';
    
        item.append(removeFormButton);
    
        removeFormButton.addEventListener('click', (e) => {
            e.preventDefault();
            // remove the li for the tag form
            item.remove();
        });
    }
    
    document
        .querySelectorAll('ul.tags li')
        .forEach((tag) => {
            addTagFormDeleteLink(tag)
        })
    
    // ... the rest of the block from above
    
    
    

    const form = document.getElementById('meetup_form');
    const form_select_addPerson = document.getElementById('company_addPerson');
    const form_select_position = document.getElementById('meetup_position');

    const updateForm = async (data, url, method) => {
      const req = await fetch(url, {
        method: method,
        body: data,
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
            'charset': 'utf-8'
        }
      });

      const text = await req.text();

      return text;
    };

    const parseTextToHtml = (text) => {
      const parser = new DOMParser();
      const html = parser.parseFromString(text, 'text/html');

      return html;
    };

    const changeOptions = async (e) => {
      /*const requestBody = e.target.getAttribute('name') + '=' + e.target.value + "&ajax=1";
      const updateFormResponse = await updateForm(requestBody, form.getAttribute('action'), form.getAttribute('method'));
      const html = parseTextToHtml(updateFormResponse);

      const new_form = html.getElementById('meetup_form');
      form.innerHTML = new_form.innerHTML;
      */
      var url = "/task/edit/2?ajax=1";
            var formSerialize = $($('form[name="company"]')).serialize();
            //var lastId = $('#individual_form_id').val();

            $.ajax({
                url: url, data: formSerialize, success: function (response) {
                   if (typeof response === 'string') {
                      
                        $('form[name="company"]').closest('.container').replaceWith($(response));
                   }
                }
                });
    };

    document.addEventListener("change", function(e) { // e = event object
      if (e.target && e.target.matches(".addPerson")) {
        const clickedVideoContainer = e.target;
        changeOptions(e)
        // do stuff with `clickedVideoContainer`
      }
    });

    document.addEventListener("click", function(e) { // e = event object
      console.log(e.target.matches(".add_item_link"));
      if (e.target && e.target.matches(".add_item_link")) {
        const clickedVideoContainer = e.target;
        addFormToCollection(e)
        // do stuff with `clickedVideoContainer`
      }
    });
    //

    //form_select_addPerson.addEventListener('change', (e) => changeOptions(e));

}