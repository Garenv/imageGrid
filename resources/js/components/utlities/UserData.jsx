export const getUserIdFromMeta = () => {
    const meta = document.querySelector('meta[name="userInfo"]');
    return meta ? meta.content : null;
};
