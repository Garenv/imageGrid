import React, { useState } from 'react';
import Button from 'react-bootstrap/Button';
import Modal from 'react-bootstrap/Modal';

function SharedModal({ primaryClick, launchButtonTitle, title, body, customStyle, type, onChange, placeHolder}) {
    const [show, setShow] = useState(false);
    const [isDisabled, setIsDisabled] = useState(true);
    const handleClose = () => setShow(false);
    const handleShow = () => {
        setIsDisabled(true);
        setShow(true);
    }

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
                <Modal.Footer>
                    <Button variant="secondary" data-cy="modal-secondary" onClick={handleClose}>Close</Button>
                    <Button className="bg-danger" disabled={isDisabled} data-cy="modal-primary" onClick={primaryClick}>OK</Button>
                </Modal.Footer>
            </Modal>
        </>
    );
}

export default SharedModal;
