import React, { useEffect, useState } from "react";
import { Button, Modal, ModalHeader, ModalBody, Form, FormGroup, ModalFooter, Input, Label } from 'reactstrap';
import axios from 'axios';
import Swal from 'sweetalert2';

const ArtistList = () => {
    const [artists, setArtists] = useState([]);
    const [modalOpen, setModalOpen] = useState(false);
    const [artistEdit, setArtistEdit] = useState(null);
    const [isEdit, setIsEdit] = useState(false);
    const [modalDeleteOpen, setModalDeleteOpen] = useState(false);
    const [artistDelete, setArtistDelete] = useState(null);

    useEffect(() => {
        fetchArtists();
    }, []);

    const fetchArtists = async () => {
        try {
            const response = await axios.get('http://localhost/multimedios/Proyecto-grupal/API/artists.php');
            setArtists(response.data);
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

    const openEditModal = (artist) => {
        setArtistEdit(artist);
        setIsEdit(!!artist);
        setModalOpen(true);
    };

    const openDeleteModal = (artist) => {
        setArtistDelete(artist);
        setModalDeleteOpen(true);
    };

    const handleInputChange = (e) => {
        const { name, value } = e.target;
        setArtistEdit({ ...artistEdit, [name]: value });
    };

    const handleArtistSave = async () => {
        try {
            if (isEdit) {
                await axios.put('http://localhost/multimedios/Proyecto-grupal/API/artists.php', artistEdit);
                Swal.fire({
                    icon: 'success',
                    title: 'Actualizado',
                    text: 'Artista actualizado con éxito',
                    background: '#6a378a', // morado para éxito
                    color: 'white',
                    iconColor: 'white'
                });
            } else {
                await axios.post('http://localhost/multimedios/Proyecto-grupal/API/artists.php', artistEdit);
                Swal.fire({
                    icon: 'success',
                    title: 'Creado',
                    text: 'Artista creado con éxito',
                    background: '#1ced43', // verde claro para creado
                    color: 'white',
                    iconColor: 'white'
                });
            }
            toggleModal();
            fetchArtists();
        } catch (error) {
            console.error('Error en el API', error);
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'Error al guardar el artista',
                background: '#dc3545', // rojo para error
                color: 'white',
                iconColor: 'white'
            });
        }
    };

    const handleArtistDelete = async () => {
        try {
            await axios.delete('http://localhost/multimedios/Proyecto-grupal/API/artists.php', {
                data: { idArtist: artistDelete.idArtist }
            });
            Swal.fire({
                icon: 'success',
                title: 'Eliminado',
                text: 'Artista eliminado con éxito',
                background: '#75101e', // rojo oscuro para eliminado
                color: 'white',
                iconColor: 'white'
            });
            toggleDeleteModal();
            fetchArtists();
        } catch (error) {
            console.error('Error en el API', error);
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'Error al eliminar el artista',
                background: '#dc3545', // rojo para error
                color: 'white',
                iconColor: 'white'
            });
        }
    };

    return (
        <div className="container">
            <br /><br /><br />
            <Button color="primary" onClick={() => openEditModal(null)}>Agregar Artista</Button>

            <table className="table table-striped table-hover table-borderless table-primary align-middle">
                <thead className="thead-dark">
                    <tr>
                        <th>Id</th>
                        <th>Nombre</th>
                        <th>Biografía</th>
                        <th>Fecha de Creación</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody className="table-group-divider">
                    {
                        artists.map(
                            item => (
                                <tr key={item.idArtist}>
                                    <td>{item.idArtist}</td>
                                    <td>{item.name}</td>
                                    <td>{item.biography}</td>
                                    <td>{item.creationDate}</td>
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
                <ModalHeader toggle={toggleModal}>{isEdit ? 'Editar Artista' : 'Agregar Artista'}</ModalHeader>
                <ModalBody>
                    <Form>
                        <FormGroup>
                            <Label for="name">Nombre</Label>
                            <Input type="text" name="name" id="name" value={artistEdit?.name || ''} onChange={handleInputChange} />
                        </FormGroup>
                        <FormGroup>
                            <Label for="biography">Biografía</Label>
                            <Input type="text" name="biography" id="biography" value={artistEdit?.biography || ''} onChange={handleInputChange} />
                        </FormGroup>
                        <FormGroup>
                            <Label for="creationDate">Fecha de Creación</Label>
                            <Input type="date" name="creationDate" id="creationDate" value={artistEdit?.creationDate || ''} onChange={handleInputChange} />
                        </FormGroup>
                    </Form>
                </ModalBody>
                <ModalFooter>
                    <Button color="primary" onClick={handleArtistSave}>Guardar</Button>{' '}
                    <Button color="secondary" onClick={toggleModal}>Cancelar</Button>
                </ModalFooter>
            </Modal>

            <Modal isOpen={modalDeleteOpen} toggle={toggleDeleteModal}>
                <ModalHeader toggle={toggleDeleteModal}>Eliminar Artista</ModalHeader>
                <ModalBody>
                    <p>¿Desea borrar el elemento?</p>
                </ModalBody>
                <ModalFooter>
                    <Button color="danger" onClick={handleArtistDelete}>Borrar</Button>{' '}
                    <Button color="secondary" onClick={toggleDeleteModal}>Cancelar</Button>
                </ModalFooter>
            </Modal>
        </div>
    );
};

export default ArtistList;
