import React, { useEffect, useState } from "react";
import { Button, Modal, ModalHeader, ModalBody, Form, FormGroup, ModalFooter, Input, Label } from 'reactstrap';
import axios from 'axios';
import Swal from 'sweetalert2';

const UserList = () => {
    const [users, setUsers] = useState([]);
    const [modalOpen, setModalOpen] = useState(false);
    const [userEdit, setUserEdit] = useState(null);
    const [isEdit, setIsEdit] = useState(false);
    const [modalDeleteOpen, setModalDeleteOpen] = useState(false);
    const [userDelete, setUserDelete] = useState(null);

    useEffect(() => {
        fetchUsers();
    }, []);

    const fetchUsers = async () => {
        try {
            const response = await axios.get('http://localhost/multimedios/Proyecto-grupal/API/users.php');
            setUsers(response.data);
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

    const openEditModal = (user) => {
        setUserEdit(user);
        setIsEdit(!!user);
        setModalOpen(true);
    };

    const openDeleteModal = (user) => {
        setUserDelete(user);
        setModalDeleteOpen(true);
    };

    const handleInputChange = (e) => {
        const { name, value } = e.target;
        setUserEdit({ ...userEdit, [name]: value });
    };

    const handleUserSave = async () => {
        try {
            if (isEdit) {
                await axios.put('http://localhost/multimedios/Proyecto-grupal/API/users.php', userEdit);
                Swal.fire({
                    icon: 'success',
                    title: 'Actualizado',
                    text: 'Usuario actualizado con éxito',
                    background: '#6a378a', // verde para éxito
                    color: 'white',
                    iconColor: 'white'
                });
            } else {
                await axios.post('http://localhost/multimedios/Proyecto-grupal/API/users.php', userEdit);
                Swal.fire({
                    icon: 'success',
                    title: 'Creado',
                    text: 'Usuario creado con éxito',
                    background: '#1ced43', // azul para creado
                    color: 'white',
                    iconColor: 'white'
                });
            }
            toggleModal();
            fetchUsers();
        } catch (error) {
            console.error('Error en el API', error);
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'Error al guardar el usuario',
                background: '#dc3545', // rojo para error
                color: 'white',
                iconColor: 'white'
            });
        }
    };

    const handleUserDelete = async () => {
        try {
            await axios.delete('http://localhost/multimedios/Proyecto-grupal/API/users.php', {
                data: { idUser: userDelete.idUser }
            });
            Swal.fire({
                icon: 'success',
                title: 'Eliminado',
                text: 'Usuario eliminado con éxito',
                background: '#75101e', // verde para éxito
                color: 'white',
                iconColor: 'white'
            });
            toggleDeleteModal();
            fetchUsers();
        } catch (error) {
            console.error('Error en el API', error);
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'Error al eliminar el usuario',
                background: '#dc3545', // rojo para error
                color: 'white',
                iconColor: 'white'
            });
        }
    };

    return (
        <div className="container">
            <br /><br /><br />
            <Button color="primary" onClick={() => openEditModal(null)}>Agregar Usuario</Button>

            <table className="table table-striped table-hover table-borderless table-primary align-middle">
                <thead className="thead-dark">
                    <tr>
                        <th>Id</th>
                        <th>Nombre</th>
                        <th>Email</th>
                        <th>Password</th>
                        <th>Fecha de Registro</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody className="table-group-divider">
                    {
                        users.map(
                            item => (
                                <tr key={item.idUser}>
                                    <td>{item.idUser}</td>
                                    <td>{item.name}</td>
                                    <td>{item.email}</td>
                                    <td>{item.password}</td>
                                    <td>{item.releaseDate}</td>
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
                <ModalHeader toggle={toggleModal}>{isEdit ? 'Editar Usuario' : 'Agregar Usuario'}</ModalHeader>
                <ModalBody>
                    <Form>
                        <FormGroup>
                            <Label for="name">Nombre</Label>
                            <Input type="text" name="name" id="name" value={userEdit?.name || ''} onChange={handleInputChange} />
                        </FormGroup>
                        <FormGroup>
                            <Label for="email">Email</Label>
                            <Input type="email" name="email" id="email" value={userEdit?.email || ''} onChange={handleInputChange} />
                        </FormGroup>
                        <FormGroup>
                            <Label for="password">Password</Label>
                            <Input type="password" name="password" id="password" value={userEdit?.password || ''} onChange={handleInputChange} />
                        </FormGroup>
                        <FormGroup>
                            <Label for="releaseDate">Fecha de Registro</Label>
                            <Input type="date" name="releaseDate" id="releaseDate" value={userEdit?.releaseDate || ''} onChange={handleInputChange} />
                        </FormGroup>
                    </Form>
                </ModalBody>
                <ModalFooter>
                    <Button color="primary" onClick={handleUserSave}>Guardar</Button>{' '}
                    <Button color="secondary" onClick={toggleModal}>Cancelar</Button>
                </ModalFooter>
            </Modal>

            <Modal isOpen={modalDeleteOpen} toggle={toggleDeleteModal}>
                <ModalHeader toggle={toggleDeleteModal}>Eliminar Usuario</ModalHeader>
                <ModalBody>
                    <p>¿Desea borrar el elemento?</p>
                </ModalBody>
                <ModalFooter>
                    <Button color="danger" onClick={handleUserDelete}>Borrar</Button>{' '}
                    <Button color="secondary" onClick={toggleDeleteModal}>Cancelar</Button>
                </ModalFooter>
            </Modal>
        </div>
    );
};

export default UserList;
