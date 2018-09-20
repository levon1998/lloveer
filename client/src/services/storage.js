import localforage from 'localforage';

localforage.config({
    driver: localforage.LOCALSTORAGE, // Force WebSQL; same as using setDriver()
    name: 'lucardofrontend',
    version: 1.0,
    size: 4980736, // Size of database, in bytes. WebSQL-only for now.
    storeName: 'frontend', // Should be alphanumeric, with underscores.
    description: 'some description'
});

export const storage = {
    set: (key, value) => localforage
        .setItem(key, value)
        .catch(err => console.warn(`error while trying to set '${key}' to '${value}' into storage: ${err}`)),
    get: key => localforage
        .getItem(key)
        .catch(err => console.warn(`error while trying to get '${key}' key from storage: ${err}`)),

    remove: key => localforage
        .removeItem(key)
        .catch(err => console.warn(`error while trying to remove '${key}' key from storage: ${err}`))

    // @todo implement removeItem(), clear()
};
