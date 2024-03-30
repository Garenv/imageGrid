export const getUserIdFromMeta = () => {
    const meta = document.querySelector('meta[name="userInfo"]');
    return meta ? meta.content : null;
};

export const getNameFromMeta = () => {
    const meta = document.querySelector('meta[name="userName"]');
    return meta ? meta.content : null;
};

// Might need later
export const getCSRFToken = () => {
    const meta = document.querySelector('meta[name="csrf-token"]');
    return meta ? meta.content : null;
};
