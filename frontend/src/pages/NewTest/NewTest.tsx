import React, {useEffect, useState} from 'react';
import {Button, Col, Container, Form, FormCheck, FormControl, InputGroup, Row} from "react-bootstrap";
import {useParams} from 'react-router-dom';
import {ITest} from "../../types/types";
import {useObserver} from "mobx-react-lite";
import {useRootStore} from "../../RootStateContext";

interface NewTestParams {
    id: string;
}

export interface token {
    token: string;
}

const NewTest = () => {
    const { newTestStore } = useRootStore();

    const [test, setTest] = useState<ITest | null>(null);
    const params = useParams<NewTestParams>()
    useEffect(() => {
        fetchTest()
    },[])

    async function fetchTest() {
        try {
            $http.get('/api/tests/171').then((response: any) => {setTest(response.data)})
        } catch (e) {
            alert(e)
        }
    }

    function push(){
    }

    function fetch(){
    }

    return useObserver(() => (
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
                    <Button className="mt-3" onClick={fetch}>Fetch</Button>
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
    ))
};

export default NewTest;
