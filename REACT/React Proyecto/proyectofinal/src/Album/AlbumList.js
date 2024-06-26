import React, { useEffect, useState } from "react";
import { Button, Modal, ModalHeader, ModalBody, Form, FormGroup, ModalFooter, Input, Label } from 'reactstrap';
import axios from 'axios';
import Swal from 'sweetalert2';

const AlbumList = () => {
    const [albums, setAlbums] = useState([]);
    const [modalOpen, setModalOpen] = useState(false);
    const [albumEdit, setAlbumEdit] = useState(null);
    const [isEdit, setIsEdit] = useState(false);
    const [modalDeleteOpen, setModalDeleteOpen] = useState(false);
    const [albumDelete, setAlbumDelete] = useState(null);

    useEffect(() => {
        fetchAlbums();
    }, []);

    const fetchAlbums = async () => {
        try {
            const response = await axios.get('http://localhost/multimedios/Proyecto-grupal/API/albums.php');
            setAlbums(response.data);
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

    const openEditModal = (album) => {
        setAlbumEdit(album);
        setIsEdit(!!album);
        setModalOpen(true);
    };

    const openDeleteModal = (album) => {
        setAlbumDelete(album);
        setModalDeleteOpen(true);
    };

    const handleInputChange = (e) => {
        const { name, value } = e.target;
        setAlbumEdit({ ...albumEdit, [name]: value });
    };

    const handleAlbumSave = async () => {
        try {
            if (isEdit) {
                await axios.put('http://localhost/multimedios/Proyecto-grupal/API/albums.php', albumEdit);
                Swal.fire({
                    icon: 'success',
                    title: 'Actualizado',
                    text: 'Álbum actualizado con éxito',
                    background: '#6a378a', // verde para éxito
                    color: 'white',
                    iconColor: 'white'
                });
            } else {
                await axios.post('http://localhost/multimedios/Proyecto-grupal/API/albums.php', albumEdit);
                Swal.fire({
                    icon: 'success',
                    title: 'Creado',
                    text: 'Álbum creado con éxito',
                    background: '#1ced43', // azul para creado
                    color: 'white',
                    iconColor: 'white'
                });
            }
            toggleModal();
            fetchAlbums();
        } catch (error) {
            console.error('Error en el API', error);
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'Error al guardar el álbum',
                background: '#dc3545', // rojo para error
                color: 'white',
                iconColor: 'white'
            });
        }
    };

    const handleAlbumDelete = async () => {
        try {
            await axios.delete('http://localhost/multimedios/Proyecto-grupal/API/albums.php', {
                data: { idAlbums: albumDelete.idAlbums }
            });
            Swal.fire({
                icon: 'success',
                title: 'Eliminado',
                text: 'Álbum eliminado con éxito',
                background: '#75101e', // verde para éxito
                color: 'white',
                iconColor: 'white'
            });
            toggleDeleteModal();
            fetchAlbums();
        } catch (error) {
            console.error('Error en el API', error);
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'Error al eliminar el álbum',
                background: '#dc3545', // rojo para error
                color: 'white',
                iconColor: 'white'
            });
        }
    };

    return (
        <div className="container">
            <br /><br /><br />
            <Button color="primary" onClick={() => openEditModal(null)}>Agregar Álbum</Button>

            <table className="table table-striped table-hover table-borderless table-primary align-middle">
                <thead className="thead-dark">
                    <tr>
                        <th>Id</th>
                        <th>Artista</th>
                        <th>Título</th>
                        <th>Fecha de Lanzamiento</th>
                        <th>Género</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody className="table-group-divider">
                    {
                        albums.map(
                            item => (
                                <tr key={item.idAlbums}>
                                    <td>{item.idAlbums}</td>
                                    <td>{item.idArtist}</td>
                                    <td>{item.title}</td>
                                    <td>{item.releaseDate}</td>
                                    <td>{item.genre}</td>
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
                <ModalHeader toggle={toggleModal}>{isEdit ? 'Editar Álbum' : 'Agregar Álbum'}</ModalHeader>
                <ModalBody>
                    <Form>
                        <FormGroup>
                            <Label for="idArtist">Artista</Label>
                            <Input type="text" name="idArtist" id="idArtist" value={albumEdit?.idArtist || ''} onChange={handleInputChange} />
                        </FormGroup>
                        <FormGroup>
                            <Label for="title">Título</Label>
                            <Input type="text" name="title" id="title" value={albumEdit?.title || ''} onChange={handleInputChange} />
                        </FormGroup>
                        <FormGroup>
                            <Label for="releaseDate">Fecha de Lanzamiento</Label>
                            <Input type="date" name="releaseDate" id="releaseDate" value={albumEdit?.releaseDate || ''} onChange={handleInputChange} />
                        </FormGroup>
                        <FormGroup>
                            <Label for="genre">Género</Label>
                            <Input type="text" name="genre" id="genre" value={albumEdit?.genre || ''} onChange={handleInputChange} />
                        </FormGroup>
                    </Form>
                </ModalBody>
                <ModalFooter>
                    <Button color="primary" onClick={handleAlbumSave}>Guardar</Button>{' '}
                    <Button color="secondary" onClick={toggleModal}>Cancelar</Button>
                </ModalFooter>
            </Modal>

            <Modal isOpen={modalDeleteOpen} toggle={toggleDeleteModal}>
                <ModalHeader toggle={toggleDeleteModal}>Eliminar Álbum</ModalHeader>
                <ModalBody>
                    <p>¿Desea borrar el elemento?</p>
                </ModalBody>
                <ModalFooter>
                    <Button color="danger" onClick={handleAlbumDelete}>Borrar</Button>{' '}
                    <Button color="secondary" onClick={toggleDeleteModal}>Cancelar</Button>
                </ModalFooter>
            </Modal>
        </div>
    );
};

export default AlbumList;
