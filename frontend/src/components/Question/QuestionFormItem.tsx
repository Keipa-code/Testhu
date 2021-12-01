import React, {FC, useEffect} from 'react';
import {Accordion, Col, Form, Row} from "react-bootstrap";
import {IQuestion, QuestionFormStore} from "./QuestionFormStore";
import AnswerForm from "./AnswerForm";
import {observer} from "mobx-react-lite";

interface QuestionFormItemProps {
    qKey?: number;
    question?: IQuestion;
    inputChange?: QuestionFormStore["inputChange"];
}

const QuestionFormItem: FC<QuestionFormItemProps> = observer((
    {
        qKey,
        question,
        inputChange
    }) => {

    useEffect(() => {
        inputChange(qKey, qKey + 1, 'position')
    }, [])

    const handleChange = (e: React.ChangeEvent<HTMLInputElement>) => {
        inputChange(qKey, e.target.value, e.target.name)
    };

    return (
        <div>
            <Accordion className="mb-3" defaultActiveKey="0">
                <Accordion.Item eventKey={String(qKey)}>
                    <Accordion.Header>Вопрос № {question.position}</Accordion.Header>
                    <Accordion.Body>
                        <Form>
                            <Form.Group className="mb-3">
                                <Form.Label>Текст вопроса</Form.Label>
                                <Form.Control
                                    name="questionText"
                                    placeholder="Введите текст вопроса"
                                    value={question.questionText}
                                    onChange={handleChange}
                                />
                                <Form.Text className="text-muted">
                                    Хорошо подумайте над текстом, что бы было понятно
                                </Form.Text>
                            </Form.Group>
                            <AnswerForm qKey={qKey}/>
                            <Row>
                                <Col xs="5">
                                    <Form.Label>Число баллов за правильный ответ</Form.Label>
                                    <Form.Control
                                        name="points"
                                        type="number"
                                        value={question.points}
                                        placeholder="Введите число баллов за правильный ответ"
                                        onChange={handleChange}
                                    />
                                </Col>
                            </Row>
                        </Form>
                    </Accordion.Body>
                </Accordion.Item>
            </Accordion>
        </div>
    );
})

export default QuestionFormItem;
