import React, { useEffect, useState } from "react";
import { Button, Modal, ModalHeader, ModalBody, Form, FormGroup, ModalFooter, Input, Label } from 'reactstrap';
import axios from 'axios';
import Swal from 'sweetalert2';

const PurchaseList = () => {
    const [purchases, setPurchases] = useState([]);
    const [modalOpen, setModalOpen] = useState(false);
    const [purchaseEdit, setPurchaseEdit] = useState(null);
    const [isEdit, setIsEdit] = useState(false);
    const [modalDeleteOpen, setModalDeleteOpen] = useState(false);
    const [purchaseDelete, setPurchaseDelete] = useState(null);

    useEffect(() => {
        fetchPurchases();
    }, []);

    const fetchPurchases = async () => {
        try {
            const response = await axios.get('http://localhost/multimedios/Proyecto-grupal/API/purchases.php');
            setPurchases(response.data);
        } catch (error) {
            console.error('Error al ejecutar', error);
        }
    };

    const toggleModal = () => {
        setModalOpen(!modalOpen);
    };

    const toggleDeleteModal = () => {
        setModalDeleteOpen(!modalDeleteOpen);
    };

    const openEditModal = (purchase) => {
        setPurchaseEdit(purchase);
        setIsEdit(!!purchase);
        setModalOpen(true);
    };

    const openDeleteModal = (purchase) => {
        setPurchaseDelete(purchase);
        setModalDeleteOpen(true);
    };

    const handleInputChange = (e) => {
        const { name, value } = e.target;
        setPurchaseEdit({ ...purchaseEdit, [name]: value });
    };

    const handlePurchaseSave = async () => {
        try {
            if (isEdit) {
                await axios.put('http://localhost/multimedios/Proyecto-grupal/API/purchases.php', purchaseEdit);
                Swal.fire({
                    icon: 'success',
                    title: 'Actualizado',
                    text: 'Compra actualizada con éxito',
                    background: '#6a378a', // morado para éxito
                    color: 'white',
                    iconColor: 'white'
                });
            } else {
                await axios.post('http://localhost/multimedios/Proyecto-grupal/API/purchases.php', purchaseEdit);
                Swal.fire({
                    icon: 'success',
                    title: 'Creado',
                    text: 'Compra creada con éxito',
                    background: '#1ced43', // verde claro para creado
                    color: 'white',
                    iconColor: 'white'
                });
            }
            toggleModal();
            fetchPurchases();
        } catch (error) {
            console.error('Error en el API', error);
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'Error al guardar la compra',
                background: '#dc3545', // rojo para error
                color: 'white',
                iconColor: 'white'
            });
        }
    };

    const handlePurchaseDelete = async () => {
        try {
            await axios.delete('http://localhost/multimedios/Proyecto-grupal/API/purchases.php', {
                data: { idPurchase: purchaseDelete.idPurchase }
            });
            Swal.fire({
                icon: 'success',
                title: 'Eliminado',
                text: 'Compra eliminada con éxito',
                background: '#75101e', // rojo oscuro para eliminado
                color: 'white',
                iconColor: 'white'
            });
            toggleDeleteModal();
            fetchPurchases();
        } catch (error) {
            console.error('Error en el API', error);
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'Error al eliminar la compra',
                background: '#dc3545', // rojo para error
                color: 'white',
                iconColor: 'white'
            });
        }
    };

    return (
        <div className="container">
            <br /><br /><br />
            <Button color="primary" onClick={() => openEditModal(null)}>Agregar Compra</Button>

            <table className="table table-striped table-hover table-borderless table-primary align-middle">
                <thead className="thead-dark">
                    <tr>
                        <th>Id</th>
                        <th>Usuario</th>
                        <th>Canción</th>
                        <th>Fecha de Compra</th>
                        <th>Precio</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody className="table-group-divider">
                    {
                        purchases.map(
                            item => (
                                <tr key={item.idPurchase}>
                                    <td>{item.idPurchase}</td>
                                    <td>{item.idUser}</td>
                                    <td>{item.idSong}</td>
                                    <td>{item.datePurchase}</td>
                                    <td>{item.price}</td>
                                    <td>
                                        <Button color="info" onClick={() => openEditModal(item)}>Editar</Button>{' '}
                                        <Button color="danger" onClick={() => openDeleteModal(item)}>Eliminar</Button>
                                    </td>
                                </tr>
                            )
                        )
                    }
                </tbody>
            </table>

            <Modal isOpen={modalOpen} toggle={toggleModal}>
                <ModalHeader toggle={toggleModal}>{isEdit ? 'Editar Compra' : 'Agregar Compra'}</ModalHeader>
                <ModalBody>
                    <Form>
                        <FormGroup>
                            <Label for="idUser">Usuario</Label>
                            <Input type="text" name="idUser" id="idUser" value={purchaseEdit?.idUser || ''} onChange={handleInputChange} />
                        </FormGroup>
                        <FormGroup>
                            <Label for="idSong">Canción</Label>
                            <Input type="text" name="idSong" id="idSong" value={purchaseEdit?.idSong || ''} onChange={handleInputChange} />
                        </FormGroup>
                        <FormGroup>
                            <Label for="datePurchase">Fecha de Compra</Label>
                            <Input type="date" name="datePurchase" id="datePurchase" value={purchaseEdit?.datePurchase || ''} onChange={handleInputChange} />
                        </FormGroup>
                        <FormGroup>
                            <Label for="price">Precio</Label>
                            <Input type="text" name="price" id="price" value={purchaseEdit?.price || ''} onChange={handleInputChange} />
                        </FormGroup>
                    </Form>
                </ModalBody>
                <ModalFooter>
                    <Button color="primary" onClick={handlePurchaseSave}>Guardar</Button>{' '}
                    <Button color="secondary" onClick={toggleModal}>Cancelar</Button>
                </ModalFooter>
            </Modal>

            <Modal isOpen={modalDeleteOpen} toggle={toggleDeleteModal}>
                <ModalHeader toggle={toggleDeleteModal}>Eliminar Compra</ModalHeader>
                <ModalBody>
                    <p>¿Desea borrar el elemento?</p>
                </ModalBody>
                <ModalFooter>
                    <Button color="danger" onClick={handlePurchaseDelete}>Borrar</Button>{' '}
                    <Button color="secondary" onClick={toggleDeleteModal}>Cancelar</Button>
                </ModalFooter>
            </Modal>
        </div>
    );
};

export default PurchaseList;
