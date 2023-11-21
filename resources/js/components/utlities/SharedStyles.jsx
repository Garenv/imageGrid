// sharedStyles.js
import { makeStyles } from '@material-ui/core/styles';

export const useSharedStyles = makeStyles((theme) => ({
    centered: {
        position: 'fixed',
        top: '50%',
        left: '50%',
        transform: 'translate(-50%, -50%)',
        zIndex: -10000
    }
}));
