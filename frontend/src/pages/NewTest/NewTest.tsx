import React, {FC, useEffect} from 'react';
import {Button, Col, Container, Form, FormControl, InputGroup, Row} from "react-bootstrap";
import {useParams} from 'react-router-dom';
import {observer} from "mobx-react-lite";
import {useRootStore} from "../../RootStateContext";
import {storage} from "../../utils/tools";
import QuestionFormList from "../../components/Question/QuestionFormList";

interface NewTestParams {
    id: string;
}

export interface token {
    token: string;
}

const NewTest: FC = observer(() => {
    const {newTestStore} = useRootStore()

    let testName = '321'
    const params = useParams<NewTestParams>()
    useEffect(() => {
        newTestStore.getFromStorage('newTest')
        const interval = setInterval(() => newTestStore.setToStorage('newTest'), 360000)
        return () => clearInterval(interval)
    }, [])

    function handleChange(e: React.ChangeEvent<HTMLInputElement>) {
        newTestStore.inputChange(e.target.value, e.target.name)
    }

    function setStorage() {
        storage.set('test', {hour: 10, minute: 50})
    }

    function getFromStorage() {
        console.log(storage.get('newTest'))
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
                                name="testName"
                                placeholder="Введите наименование"
                                aria-describedby="basic-addon2"
                                value={newTestStore.test.testName}
                                onChange={handleChange}
                            />
                        </Form.Group>
                        <Form.Group className="mb-3">
                            <Form.Label>Описание теста</Form.Label>
                            <Form.Control
                                as="textarea"
                                rows={4}
                                name="description"
                                placeholder="Введите описание"
                                aria-describedby="basic-addon2"
                                value={newTestStore.test.description}
                                onChange={handleChange}
                            />
                        </Form.Group>
                        <Form.Group className="mb-3">
                            <Form.Label>Правила теста</Form.Label>
                            <Form.Control
                                as="textarea"
                                rows={4}
                                name="rules"
                                placeholder="Введите правила теста"
                                aria-describedby="basic-addon2"
                                value={newTestStore.test.rules}
                                onChange={handleChange}
                            />
                        </Form.Group>

                    </Form>
                    <Form>
                        <Form.Label>Ограничение по времени</Form.Label>
                        <Row className="align-items-center">
                            <Col xs="4">
                                <InputGroup className="mb-3">
                                    <FormControl
                                        name="hour"
                                        aria-describedby="basic-addon2"
                                        value={newTestStore.test.timeLimit.hour}
                                        onChange={handleChange}
                                    />
                                    <InputGroup.Text>ч.</InputGroup.Text>
                                    <FormControl
                                        name="minute"
                                        aria-describedby="basic-addon2"
                                        value={newTestStore.test.timeLimit.minute}
                                        onChange={handleChange}
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
                    <Button className="mt-3" onClick={setStorage}>Добавить предисловие</Button>
                    <Button className="ms-3 mt-3" onClick={getFromStorage}>Fetch</Button>
                    <Form>
                        <Form.Check className="mt-3"
                                    label={'Разрешить смотреть список неправильных ответов после теста'}/>
                        <Form.Check className="mb-5" label={'Сделать все результаты прохождения публичными'}/>
                    </Form>
                    <QuestionFormList />
                </Col>
                <Col className="col-sm-4">
                </Col>
            </Row>
            <Row>
                <Col className="mb-5" md={{span: 3, offset: 9}}>
                    <Button>Сохранить</Button>
                </Col>
            </Row>
        </Container>
    )
});

export default NewTest;
