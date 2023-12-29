import React, {useCallback, useState} from 'react';
import Button from 'react-bootstrap/Button';
import Modal from 'react-bootstrap/Modal';
import {render} from "react-dom";

function SharedModal({ primaryClick, launchButtonTitle, title, body, customStyle}) {
    const [show, setShow] = useState(false);
    const [isDisabled, setIsDisabled] = useState(true);
    const handleClose = () => setShow(false);
    const handleShow = () => {
        setIsDisabled(true)
        setShow(true);
    }

    const onInputChange = useCallback((event) => {
        const inputValue = event.target.value;
        setIsDisabled(inputValue !== 'CONFIRM');
    }, []);


    return (
        <>
            <Button variant="primary" data-cy="modal-launch-button" style={customStyle} onClick={handleShow}>
                {launchButtonTitle}
            </Button>

            <Modal
                show={show}
                onHide={handleClose}
                backdrop="static"
                keyboard={false}
                aria-labelledby="contained-modal-title-vcenter"
                centered
            >
                <Modal.Header closeButton>
                    <Modal.Title data-cy='modal-title'>{title}</Modal.Title>
                </Modal.Header>
                <Modal.Body data-cy="modal-body">{body}</Modal.Body>
                <input data-cy="modal-input" onChange={onInputChange}/>
                <Modal.Footer>
                    <Button variant="secondary" data-cy="modal-secondary" onClick={handleClose}>Close</Button>
                    <Button variant="primary" disabled={isDisabled} data-cy="modal-primary" onClick={primaryClick}>Understood</Button>
                </Modal.Footer>
            </Modal>
        </>
    );
}

export default SharedModal;
