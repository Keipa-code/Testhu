import React, {useEffect, useState} from 'react';
import {Button, Col, Container, Form, FormCheck, FormControl, InputGroup, Row} from "react-bootstrap";
import {useParams} from 'react-router-dom';
import axios from "axios";
import {ITest} from "../types/types";
import TokenStore from "../stores/TokenStore";

interface NewTestParams {
    id: string;
}

export interface token {
    token: string;
}

const NewTest = () => {
    const [test, setTest] = useState<ITest | null>(null);
    const [token, setToken] = useState('');
    const params = useParams<NewTestParams>()
    useEffect(() => {
        getJWT()
    }, [])

    async function getJWT() {
        try {
            const response = await axios.post<token>(
                '/api/api/login',
                {
                    username: "frontend_anonymous",
                    password: "12345678"
                }, {
                    headers: {'Content-Type': 'application/json'}
                })
            setToken(response.data.token)
        } catch (e) {
            alert(e)
        }
    }

    async function fetchTest() {
        try {
            const response = await axios.get<ITest>(
                '/api/api/tests/' + params.id,
                {
                    headers: {'Content-Type': 'application/json'},
                    data: {auth_bearer: TokenStore.token}
                })
            setTest(response.data)
        } catch (e) {
            alert(e)
        }
    }

    function push(){
        console.log(token)
    }

    return (
        <Container>
            <Row>
                <Col className="col-sm-8">
                    <h2>Создать новый тест</h2>
                    <FormControl
                        className="mt-3"
                        placeholder="Название"
                        aria-label="Название"
                        aria-describedby="basic-addon2"
                        value={test?.testName}
                    />
                    <FormControl
                        className="mt-3"
                        placeholder="Тэги"
                        aria-label="Тэги"
                        aria-describedby="basic-addon2"
                    />
                    <Button className="mt-3" onClick={push}>Добавить предисловие</Button>
                    <Form>
                        <Form.Check className="mt-3"
                                    label={'Разрешить смотреть список неправильных ответов после теста'}/>
                        <Form.Check label={'Сделать все результаты прохождения публичными'}/>
                    </Form>
                    <h2 className="mt-4">Вопросы теста</h2>

                </Col>
                <Col className="col-sm-4">
                </Col>
            </Row>
        </Container>
    );
};

export default NewTest;
