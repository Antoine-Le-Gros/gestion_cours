document
    .querySelectorAll('.add_item_link')
    .forEach(btn => {
        btn.addEventListener("click", addFormToCollection)
    });

function addFormToCollection(e) {
    const collectionHolder = document.querySelector('.' + e.currentTarget.dataset.collectionHolderClass);

    const item = document.createElement('li');

    item.innerHTML = collectionHolder
        .dataset
        .prototype
        .replace(
            /__name__/g,
            collectionHolder.dataset.index
        );

    collectionHolder.appendChild(item);
    collectionHolder.appendChild(document.createElement('hr'));

    collectionHolder.dataset.index++;
    addTagFormDeleteLink(item);
}

document
    .querySelectorAll('ul.affectation li')
    .forEach((tag) => {
        addTagFormDeleteLink(tag)
    })

function addTagFormDeleteLink(item) {
    const removeFormButton = document.createElement('button');
    removeFormButton.innerText = 'Supprimer';
    removeFormButton.type = 'button';
    removeFormButton.classList.add('btn', 'btn-danger', 'remove-tag');

    item.append(removeFormButton);

    removeFormButton.addEventListener('click', (e) => {
        e.preventDefault();
        // remove the li for the tag form
        item.remove();
    });
}
