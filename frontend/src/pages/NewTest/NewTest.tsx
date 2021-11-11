import React, {FC, useEffect, useState} from 'react';
import {Button, Col, Container, Form, FormControl, InputGroup, Row} from "react-bootstrap";
import {useParams} from 'react-router-dom';
import {observer} from "mobx-react-lite";
import {useRootStore} from "../../RootStateContext";
import {computeTimeLimit} from "../../utils/tools";

interface NewTestParams {
    id: string;
}

export interface token {
    token: string;
}

interface TimeLimitProps {
    hour: number;
    minute: number;
}

const NewTest: FC = observer(() => {
    const {newTestStore} = useRootStore();
    const [timeLimit, setTimeLimit] = useState<TimeLimitProps>({
        minute: 0,
        hour: 0
    })

    let testName = '321'
    const params = useParams<NewTestParams>()
    useEffect(() => {
        newTestStore.getDetail('1')
        setTimeLimit(computeTimeLimit(+newTestStore.test.timeLimit))
    }, [])



    function fetchTest() {
        console.log(newTestStore.test)
    }

    function button2() {
        testName = '123'
    }

    function handleChangeName(e: React.ChangeEvent<HTMLInputElement>) {
        newTestStore.test.testName = e.target.value
    }

    function handleChangeDescription(e: React.ChangeEvent<HTMLInputElement>) {
        newTestStore.test.description = e.target.value
    }

    function handleChangeRules(e: React.ChangeEvent<HTMLInputElement>) {
        newTestStore.test.rules = e.target.value
    }

    function handleChangeTimeLimit(e: React.ChangeEvent<HTMLInputElement>) {
        newTestStore.test.rules = e.target.value
    }


    return (
        <Container>
            <Row>
                <Col className="col-sm-8">
                    <h2>Создать новый тест</h2>
                    <Form className="mt-5">
                        <Form.Group className="mb-3">
                            <Form.Label>Наименование теста</Form.Label>
                            <Form.Control
                                placeholder="Введите наименование"
                                aria-describedby="basic-addon2"
                                value={newTestStore.test.testName}
                                onChange={handleChangeName}
                            />
                        </Form.Group>
                        <Form.Group className="mb-3">
                            <Form.Label>Описание теста</Form.Label>
                            <Form.Control
                                as="textarea"
                                rows={4}
                                placeholder="Введите описание"
                                aria-describedby="basic-addon2"
                                value={newTestStore.test.description}
                                onChange={handleChangeDescription}
                            />
                        </Form.Group>
                        <Form.Group className="mb-3">
                            <Form.Label>Правила теста</Form.Label>
                            <Form.Control
                                as="textarea"
                                rows={4}
                                placeholder="Введите правила теста"
                                aria-describedby="basic-addon2"
                                value={newTestStore.test.rules}
                                onChange={handleChangeRules}
                            />
                        </Form.Group>

                    </Form>
                    <Form>
                        <Form.Label>Ограничение по времени</Form.Label>
                        <Row className="align-items-center">
                            <Col xs="4">
                                <InputGroup className="mb-3">
                                    <FormControl
                                        aria-describedby="basic-addon2"
                                        value={timeLimit.hour}
                                        onChange={handleChangeName}
                                    />
                                    <InputGroup.Text>ч.</InputGroup.Text>
                                    <FormControl
                                        aria-describedby="basic-addon2"
                                        value={timeLimit.minute}
                                        onChange={handleChangeName}
                                    />
                                    <InputGroup.Text>м.</InputGroup.Text>
                                </InputGroup>
                            </Col>
                        </Row>
                    </Form>
                    <FormControl
                        className="mt-3"
                        placeholder="Тэги"
                        aria-label="Тэги"
                        aria-describedby="basic-addon2"
                    />
                    <Button className="mt-3" onClick={fetchTest}>Добавить предисловие</Button>
                    <Button className="ms-3 mt-3" onClick={button2}>Fetch</Button>
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
            <Row>
                <Col className="mt-5" md={{span: 3, offset: 9}}>
                    <Button>Сохранить</Button>
                </Col>
            </Row>
        </Container>
    )
});

export default NewTest;
